<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\DefaultResponsible;
use App\Models\ExportGroup;
use App\Models\Item;
use App\Models\Position;
use App\Models\Responsible;
use App\Models\ResponsibleType;
use App\Services\CostLazyLoadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

class CostController extends Controller
{
    public function costLazyLoad()
    {
        return (new CostLazyLoadService())->prepareTableParams(request())
                                          ->prepareQueries()
                                          ->initLazyLoad();
    }

    public function cost(){
        $items = Item::where('parent_id', null)
                     ->where('user_id', auth()->id())
                     ->with(['defaultResponsibles', 'subItems' => function ($subitem) {
                         $subitem->with('defaultResponsibles');
                     }])->orderBy('id', 'DESC')->get();

        $additionals = Responsible::where('user_id', auth()->id())
                                  ->with('type')->orderBy('type_id')->get()->groupBy(function ($item) {
                                      return $item->type->name;
                                  });
        $additionalColumns = ResponsibleType::where('user_id', auth()->id())->orderBy('order')->get();

        $statistics = ResponsibleType::where('user_id', auth()->id())
                                     ->with(['responsibles' => function ($responsibles) {
                                         $responsibles->with('costs');
                                     }])->get();

        $total_sum = 0;
        foreach ($statistics as $statistic) {
            $sum = 0;
            foreach ($statistic->responsibles as $responsible) {

                $sum += $responsible->costs->sum('value');
            }
            $statistic->costSum = $sum;
            $total_sum += $sum;
        }

        $statistics->total_sum = $total_sum;
        $todays_costs = Cost::where('user_id', auth()->id())->whereDate('date', Carbon::today())->with(['item', 'responsibles'])->get();
        $todays_grouped_costs = $todays_costs->groupBy('item.id');
        $todays_total = $todays_costs->sum('value');
        $last_cost = Cost::where('user_id', auth()->id())->orderBy('id', 'desc')->first();

        return view('pages.costs.cost', [
            'items' => $items,
            'additionals' => $additionals,
            'additionalColumns' => $additionalColumns,
            'statistics' => $statistics,
            'todays_total' => $todays_total,
            'last_cost' => $last_cost,
            'todays_grouped_costs' => $todays_grouped_costs
        ]);
    }

    public function store(Request $request){
        $ITEM_ID = null;
        if($request->item === 'new'){
            $first_position = Position::where('user_id',auth()->id())->first();
            $pos_id = null;
            if($first_position){
                $pos_id = $first_position->id;
            }else{
                $new_position = Position::create([
                    'user_id' => auth()->id(),
                    'title' => 'Не Указано',
                    'role' => 'default'
                ]);
                $pos_id = $new_position->id;
            }
            $item = Item::create([
                'title' => $request->new_value,
                'position_id' => $pos_id,
                'user_id' => auth()->id()
            ]);
            $ITEM_ID = $item->id;
        }else{
            $ITEM_ID = $request->item;
        }
        $cost = Cost::create([
            'date' => Carbon::parse($request->date)->format('Y-m-d'),
            'item_id' => $ITEM_ID,
            'value' => preg_replace("/[^0-9]/", "", $request->sum )?preg_replace("/[^0-9]/", "", $request->sum ):0,
            'count' => $request->count,
            'comment' => $request->comment,
            'user_id' => auth()->id(),
            'cashless' => $request->cashless ?? '0',
        ]);

        $storePaths = [];
        $files = $request->file('images');
        if ($request->hasFile('images')) {
            foreach ($files as $file) {
                $storePaths[] = str_replace('public/','',$file->store("public/cost/images/$cost->id"));
            }
        }
        $cost->update([
           'images' => $storePaths,
        ]);

        $responsibles = [];
        if($request->additionals){
            foreach ($request->additionals as $additional){
                if($additional["value"]){
                    if($additional["value"] === 'new'){
                        $resp = Responsible::create([
                            'type_id' => $additional['type_id'],
                            'name' => $additional['new_value'],
                            'user_id' => auth()->id()
                        ]);
                        $responsibles[] = $resp->id;
                    }else{
                        $responsibles[] = $additional["value"];
                    }
                }
            }
        }

        $cost->responsibles()->attach($responsibles);
        $subtitle = $cost->item->title;
        if($cost->count){
            $subtitle .= '('. $cost->count .')';
        }
        foreach ($cost->responsibles as $responsible){
            $subtitle .= ','.$responsible->name;
        }
        return back()->with('success_fixed',['title' =>' Сохранено '.$request->sum , 'subtitle' => $subtitle]);
    }

    public function getStatisticRows(Request $request){
        $addit_arr = [];
        foreach ($request->additionals as $additional_id){
            if($additional_id){
                array_push($addit_arr,intval($additional_id));
            }
        }

        $costs = Cost::where('user_id',auth()->id())->with(['item' => function($item){
            $item->with('parentItem');
        },'responsibles' => function($responsible){
            $responsible->with('type');
        }])
            ->when($request->from,function ($query) use ($request){
                $query->whereDate('date','>=',Carbon::parse($request->from)->format('Y-m-d'));
            })
            ->when($request->to,function ($query) use ($request){
                $query->whereDate('date','<=',Carbon::parse($request->to)->format('Y-m-d'));
            })
            ->when($request->from_money,function ($query) use ($request){
                return $query->where('value','>=',intval(preg_replace("/[^0-9]/", "", $request->from_money)));
            })
            ->when($request->to_money,function ($query) use ($request){
                return $query->where('value','<=',intval(preg_replace("/[^0-9]/", "", $request->to_money)));
            })
            ->when(count($addit_arr),function ($query) use ($addit_arr){
                foreach ($addit_arr as $elem){
                    $query->whereHas('responsibles',function ($item) use ($elem){
                        $item->where ('responsibles.id',$elem);
                    });
                }

            })
            ->when($request->items && count($request->items) && $request->items[0] !== 'all',function ($query) use ($request){
                return $query->whereIn('item_id',$request->items);
            })
            ->orderBy('date',"DESC")->orderBy('created_at',"DESC")->get();
        $additionals = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->get();
        $data_array = [];
        foreach ($costs as $cost){
            $arr_item = [];
            $grouped_responsibles = $cost->responsibles->groupBy(function ($item){
                return $item->type->id;
            });
            array_push($arr_item,'<div class="position-relative">
                                '.Carbon::parse($cost->date)->format('d.m.Y').'
                                 <div class="table_comment_desc ">
                                    <div class="inner">
                                        <div>
                                            <span class="time">'.Carbon::parse($cost->created_at)->format('H:i').'</span>
                                        </div>
                                        <div>
                                            <span>'.Carbon::parse($cost->created_at)->format('d/m/Y').'</span>
                                        </div>
                                    </div>

                                </div>
                            </div>');
            array_push($arr_item,$cost->item->title);
            array_push($arr_item,$cost->count);
            if($cost->cashless === '1'){
                array_push($arr_item,'<span class="should_money cashless">'. CostLazyLoadService::makeMoney($cost->value) .'</span>');
            }else{
                array_push($arr_item,'<span class="should_money">'. CostLazyLoadService::makeMoney($cost->value) .'</span>');
            }
            foreach ($additionals as $additional){
                if(array_key_exists($additional->id,$grouped_responsibles->toArray())){
                    array_push($arr_item,$grouped_responsibles[$additional->id]->first()->name);
                }else{
                    array_push($arr_item,null);
                }
            }
            array_push($arr_item,$cost->comment);
            array_push($arr_item,'<a href="'. route('edit.cost.page',$cost->id) .'" class="font-18"><i class="bx bx-edit"></i></a>');
            $data_array[] = $arr_item;
        }
        $grouped_item_costs = $costs->groupBy(function ($cost){
            if($cost->item->parentItem){
                return $cost->item->parentItem->title.' / '.$cost->item->title;
            }
            return $cost->item->title;
        });
        $split_item_arr = [];
        foreach ($grouped_item_costs as $title => $grouped_item_cost){
            $lil_arr = [];
            $lil_arr['title'] = $title;
            $lil_arr['value'] = CostLazyLoadService::makeMoney($grouped_item_cost->sum('value'));
            $lil_arr['count'] = $grouped_item_cost->sum('count');
            $split_item_arr[] = $lil_arr;
        }
        $filter_chosen_string = '';
        if($request->from || $request->to){
            $filter_chosen_string .= ' Дата от ';
            $filter_chosen_string.= $request->from ? : '...';
            $filter_chosen_string.= ' до ';
            $filter_chosen_string.= $request->to ? : '...';
            $filter_chosen_string.= ',';
        }
        if($request->from_money || $request->to_money){
            $filter_chosen_string .= ' Сумма от ';
            $filter_chosen_string.= $request->from_money ? : '...';
            $filter_chosen_string.= ' до ';
            $filter_chosen_string.= $request->to_money ? : '...';
            $filter_chosen_string.= ',';
        }
        if($request->items && count($request->items) && $request->items[0] !== 'all'){
            $items = Item::whereIn('id',$request->items)->get();
            $filter_chosen_string.= ' Предметы/Услуги - '.implode(', ',$items->pluck('title')->toArray());
            $filter_chosen_string.= ',';
        }
        if(count($addit_arr)){
            $responsibles = Responsible::whereIn('id',$addit_arr)->get();
            $filter_chosen_string.= ' Ответственные - '.implode(', ',$responsibles->pluck('name')->toArray());
        }
        if(substr($filter_chosen_string,-1) === ','){
            $filter_chosen_string = substr($filter_chosen_string,0,-1);
        }

        $sum = CostLazyLoadService::makeMoney($costs->sum('value'));
        $count = $costs->sum('count');
        return response()->json([
            'success' => 'success',
            'data' => $request->all(),
            'costs' => $data_array,
            'edit_position' => 5 + count($additionals),
            'sum' => $sum,
            'count' => $count,
            'split_item_arr' => $split_item_arr,
            'show_filters' => $filter_chosen_string
        ]);
    }


    public function updateItems(Request $request){
        $changed_positions = collect([]);
        if($request->items){
            foreach ($request->items as $item){

                if(!$item['deleted']){
                    $update = [];
                    $update['required_count'] = $item['required_count'];
                    $update['required_responsible'] = $item['required_responsible'];
                    $update['title'] = $item['title'];
                    $update['group_id'] = $item['group_id'] === 'no_group' ? null : $item['group_id'];
                    if($item['position_id']){
                        $update['position_id'] = $item['position_id'];
                        $changed_positions->push($item['position_id']);
                    }
                    $item_service = Item::where('user_id',auth()->id())->where('id',$item['id'])->first();
                    if($item_service->parent_id){
                        $changed_positions->push($item_service->parentItem->position_id);
                    }else{
                        $changed_positions->push($item_service->position_id);
                    }
                    $item_service->update($update);

                    if($item['defaultResponsibles']){
                        foreach ($item['defaultResponsibles'] as $additional){
                            if($additional['value'] !== 'no_responsible' && $additional['old_value'] === 'no_responsible'){
                                DefaultResponsible::create([
                                    'item_id' => $item['id'],
                                    'responsible_id' => $additional['value']
                                ]);
                            }elseif ($additional['value'] !== 'no_responsible' && $additional['old_value'] !== 'no_responsible'){
                                DefaultResponsible::where('item_id',$item['id'])->where('responsible_id',$additional['old_value'])->update([
                                    'responsible_id' => $additional['value']
                                ]);
                            }else{
                                DefaultResponsible::where('item_id',$item['id'])->where('responsible_id',$additional['old_value'])->delete();
                            }
                        }
                    }
                }else{
                    $item_service = Item::where('user_id',auth()->id())->where('id',$item['id'])->first();
                    if($item_service){
                        if($item_service->parent_id){
                            $changed_positions->push($item_service->parentItem->position_id);
                        }else{
                            $changed_positions->push($item_service->position_id);
                        }
                        foreach ($item_service->subItems as $subitem){
                            $subitem->costs()->delete();
                            $subitem->defaultReponsibleConnections()->delete();
                        }

                        $item_service->subItems()->delete();
                        $item_service->defaultReponsibleConnections()->delete();
                        $item_service->costs()->delete();
                        $item_service->delete();
                    }
                }

            }
        }
        if($request->children){
            foreach ($request->children as $parent_group){
                foreach ($parent_group as $new_child){
                    $changed_positions->push($new_child['position_id']);

                    if(!$new_child['deleted']){
                        $new_item = Item::create([
                            'position_id' => $new_child['position_id'] ?? null,
                            'parent_id' => $new_child['parent_id'],
                            'title' => $new_child['title'],
                            'user_id' => auth()->id(),
                            'required_responsible' => $new_child['required_responsible'],
                            'required_count' => $new_child['required_count'],
                            'group_id' => $new_child['group_id'] === 'no_group' ? null : $new_child['group_id']
                        ]);
                        if($new_child['defaultResponsibles']){
                            foreach ($new_child['defaultResponsibles'] as $additional){
                                DefaultResponsible::create([
                                    'item_id' => $new_item->id,
                                    'responsible_id' => $additional['value']
                                ]);
                            }
                        }
                    }
                }
            }
        }
        if($request->new_items){
            foreach ($request->new_items as $item_group){
                foreach ($item_group as $item) {
                    if(!$item['deleted']){
                        $changed_positions->push($item['position_id'] );
                        $new_item = Item::create([
                            'position_id' => $item['position_id'] ?? null,
                            'title' => $item['title'],
                            'user_id' => auth()->id(),
                            'required_responsible' => $item['required_responsible'],
                            'required_count' => $item['required_count'],
                            'group_id' => $item['group_id'] === 'no_group' ? null : $item['group_id'],
                        ]);
                        if($item['defaultResponsibles']){
                            foreach ($item['defaultResponsibles'] as $additional){
                                DefaultResponsible::create([
                                    'item_id' => $new_item->id,
                                    'responsible_id' => $additional['value']
                                ]);
                            }
                        }
                        foreach ($item['children'] as $child){
                            if(!$child['deleted']){
                                $new_child = Item::create([
                                    'position_id' => $child['position_id'] ?? null,
                                    'parent_id' => $new_item->id,
                                    'title' => $child['title'],
                                    'user_id' => auth()->id(),
                                    'required_responsible' => $child['required_responsible'],
                                    'required_count' => $child['required_count'],
                                    'group_id' => $child['group_id'] === 'no_group' ? null : $child['group_id']
                                ]);
                                if($child['defaultResponsibles']){
                                    foreach ($child['defaultResponsibles'] as $additional){
                                        DefaultResponsible::create([
                                            'item_id' => $new_child->id,
                                            'responsible_id' => $additional['value']
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }


            }
        }

        $changed_positions = $changed_positions->unique();
        $updated_positions = Position::where('user_id',auth()->id())->whereIn('id',$changed_positions)->with(['items' => function($item){
            $item->where('user_id',auth()->id())->where('parent_id',null)->with(['defaultResponsibles','subItems' => function($subitem){
                $subitem->with('defaultResponsibles')->orderBy('id','DESC');
            }])->orderBy('id','DESC');
        }])->get();
        foreach($updated_positions as $position){
            foreach ($position->items as $item){
                $item->groupedResponsibles = $item->defaultResponsibles->groupBy('type_id');
                $group = [];
                foreach ($item->defaultResponsibles as $def_esponsibles){
                    $group[$def_esponsibles->type_id] = collect(['old_value' => $def_esponsibles->id, 'value' => $def_esponsibles->id]);
                }
                $item->groupedResponsibles = collect($group);

                foreach ($item->subItems as $subItem){
                    $group = [];
                    foreach ($subItem->defaultResponsibles as $def_esponsibles){
                        $group[$def_esponsibles->type_id] = collect(['old_value' => $def_esponsibles->id, 'value' => $def_esponsibles->id]);
                    }
                    $subItem->groupedResponsibles = collect($group);
                }
            }
        }

        return response()->json([
            'success' => 'success',
            'updated_positions' => $updated_positions,
            'data' => $request->all()
        ]);
    }


    public function getTabItems($tab_id = null){
        $first_position = Position::where('user_id',auth()->id())->first();
        if(!$first_position){
            Position::create([
                'user_id' => auth()->id(),
                'title' => 'Не Указано',
                'role' => 'default'
            ]);
        }
        $id = $tab_id;
        if(!$id){
            if(session()->get('current_tag')){
                $id = session()->get('current_tag');
            }else{
                $id = Position::where('user_id',auth()->id())->first()->id;
            }
        }
        session()->put('current_tag',$id);
        $positions = Position::where('user_id',auth()->id())
            ->select('title', 'id')
            ->get();
        $current_position = Position::where('user_id',auth()->id())
            ->where('id',$id)
            ->with(['items' => function($item){
                $item->where('user_id',auth()->id())->where('parent_id',null)->with(['defaultResponsibles','subItems' => function($subitem){
                    $subitem->with('defaultResponsibles')->orderBy('id','DESC');
                }])->orderBy('id','DESC')->select('id','title', 'position_id', 'group_id', 'parent_id', 'required_count', 'required_responsible');
            }])->select('id','title')->first();
        $export_groups = ExportGroup::where('user_id',auth()->id())->get();

        $responsibles = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->with('responsibles')->get();
        if($current_position && $current_position->items){
            foreach ($current_position->items as $item){
                $item->groupedResponsibles = $item->defaultResponsibles->groupBy('type_id');
                $group = [];
                foreach ($item->defaultResponsibles as $def_esponsibles){
                    $group[$def_esponsibles->type_id] = collect(['old_value' => $def_esponsibles->id, 'value' => $def_esponsibles->id]);
                }
                $item->groupedResponsibles = collect($group);

                foreach ($item->subItems as $subItem){
                    $group = [];
                    foreach ($subItem->defaultResponsibles as $def_esponsibles){
                        $group[$def_esponsibles->type_id] = collect(['old_value' => $def_esponsibles->id, 'value' => $def_esponsibles->id]);
                    }
                    $subItem->groupedResponsibles = collect($group);
                }
            }
        }

        return response()->json([
            'success' => 'success',
            'current' => $current_position,
            'positions' => $positions,
            'responsibles' => $responsibles,
            'export_groups' => $export_groups,
            'active_tab' => session()->get('current_tag')
        ]);
    }

    public function setActiveTab($tab_id){
        session()->set('current_tag',$tab_id);
        return response()->json([
            'success' => 'success'
        ]);
    }



    public function editCostItems(){
        return view('pages.item.edit');
    }

    public function costDelete(Request $request){
        $cost = Cost::where('user_id',auth()->id())->where('id',$request->cost)->first();
        if($cost){
            DB::table('costs_responsibles')->where('cost_id',$cost->id)->delete();
            $cost->delete();
            return redirect()->route('cost')->with('success','Выплата успешно удалена');
        }
    }
    public function toCostEditPage($id){
        $cost = Cost::where('user_id',auth()->id())->where('id',$id)->with('responsibles')->first();
        if($cost){
            $responsibles = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->with('responsibles')->get();
            $items = Item::where('user_id',auth()->id())->where('parent_id',null)->with(['defaultResponsibles','subItems' => function($subitem){
                $subitem->with('defaultResponsibles');
            }])->get();
            $cost->groupedResponsibles = $cost->responsibles->groupBy('type_id');

            return view('pages.costs.cost_edit',['cost' => $cost, 'responsibles' => $responsibles, 'items' => $items, 'prev_page' => url()->previous()]);
        }
       return redirect()->route('index');
    }

    public function updateCost(Request $request){
        $cost = Cost::where('user_id',auth()->id())->where('id',$request->cost_id)->first();
        if($cost){
            $storePaths = [];
            $files = $request->file('images');
            if ($request->hasFile('images')) {
                foreach ($files as $file) {
                    $storePaths[] = str_replace('public/', '', $file->store("public/cost/images/$cost->id"));
                }
            }

            $cost->value = preg_replace("/[^0-9]/", "", $request->sum) ? preg_replace("/[^0-9]/", "", $request->sum) : 0;
            $cost->count = $request->count;
            $cost->date = $request->date;
            $cost->item_id = $request->item;
            $cost->comment = $request->comment;
            $cost->cashless = (!empty($request->cashless) && $request->cashless == '1') ? '1' : '0';
            if (!empty($storePaths)) {
                if (!empty($cost->images)) {
                    $storePaths = array_merge($storePaths, $cost->images);
                }
                $cost->images = $storePaths;
            }
            $cost->save();
            if($request->additionals){
                $additional_array = [];
                foreach ($request->additionals as $additional){
                    if($additional['value']){
                        array_push($additional_array,$additional['value']);
                    }
                }
                $cost->responsibles()->sync($additional_array);
            }

        }

       return redirect($request->prev_page)->with('success', 'Сохранено');
    }


    public function getResponsibles(Request $request){
        $responsibles = Responsible::where('user_id',auth()->id())->where('type_id',$request->type_id)->get();
        return response()->json([
            'success' => 'success',
            'data' => $request->all(),
            'responsibles' => $responsibles
        ]);
    }
    public function getChartInfo(Request $request){

        if($request->type_id){
            $responsibles = Responsible::where('user_id',auth()->id())->when($request->responsibles && count($request->responsibles) && $request->responsibles[0] !== 'all',function ($query) use ($request){
                $query->whereIn('id',$request->responsibles);
            },function ($query) use ($request){
                $query->where('type_id',$request->type_id);
            })->with(['costs' => function($cost) use($request){
                $cost->when($request->from_date,function ($query) use ($request){
                    $query->whereDate('date','>=',Carbon::parse($request->from_date)->format('Y-m-d'));
                })
                    ->when($request->to_date,function ($query) use ($request){
                        $query->whereDate('date','<=',Carbon::parse($request->to_date)->format('Y-m-d'));
                    })
                    ->when($request->chart_items && count($request->chart_items) && $request->chart_items[0] !== 'all',function ($query) use ($request){
                        $query->whereIn('item_id',$request->chart_items);
                    });
            }])->get();
            foreach ($responsibles as $responsible){
                $responsible->groupedCosts = $responsible->costs->groupBy(function ($cost){
                    if($cost->item->parent_id){
                        return $cost->item->parentItem->title.'/'.$cost->item->title;
                    }

                    return $cost->item->title;
                });
                $listed_array = [];
                $main_average_sum = 0;
                $totalServiceCountForResponsible = 0;
                foreach ($responsible->groupedCosts as $key => $cost_group){
                    $sm_ar = [];
                    $sm_ar['key'] = $key;
                    if($request->average){
                        $average_sum = 0;
                        $valid_number = 0;
                        foreach ($cost_group as $cost){
                            $count = $cost->count ?? 1;
                            $average_sum += intval($cost->value) / $count;
                            $valid_number++;

                        }
                        if($average_sum && $valid_number){
                            $average = floor($average_sum / $valid_number);
                            $sm_ar['sum'] = $average;
                            $main_average_sum += $average;

                            if ($request->avgOnOneService && $average > 0) {
                                $totalServiceCountForResponsible++;
                            }
                        }

                    }else{
                        $sm_ar['sum'] = $cost_group->sum('value');
                    }
                    $grouped_by_value_array = [];
                    if($request->grouped){
                        $grouped_by_value = $cost_group->groupBy('value');
                        $grouped_by_value_array = [];
                        foreach ($grouped_by_value as $cost_value => $cost){
                            $grouped_by_value_array[] = ['key' => $cost_value, 'count' => count($cost)];
                        }
                    }
                    $sm_ar['grouped'] = $grouped_by_value_array;
                    $listed_array[] = $sm_ar;
                }
                if($request->average){
                    if ($request->avgOnOneService && $totalServiceCountForResponsible > 1) {
                        $main_average_sum = floor($main_average_sum / $totalServiceCountForResponsible);
                    }

                    $responsible->sum = $main_average_sum ?? 0;
                }else{
                    $responsible->sum = $responsible->costs->sum('value');
                }

                $responsible->list = $listed_array;
            }
            $responsibles = $responsibles->sortBy('sum',0,true);
            $array = [];
            foreach ($responsibles as $responsible){
                if($responsible->sum){
                    $array[] = $responsible;
                }

            }
            return response()->json([
                'success' => 'success',
                'data' => $request->all(),
                'responsibles' => $array
            ]);
        }
        return response()->json([
            'success' => 'error',
        ]);

    }

    public function toGroupEditPage(){
        $positions = Position::where('user_id',auth()->id())->get();
        return view('pages.group.edit',['positions' => $positions]);
    }

    public function updateGroups(Request $request){
        if($request->items){
            foreach ($request->items as $item){
                if(Arr::exists($item,'deleted')){
                    $db_item = Position::where('user_id',auth()->id())->where('id',$item['id'])->first();
                    $first_position = Position::where('user_id',auth()->id())->first();
                    $pos_id = null;

                    if($first_position){
                        $pos_id = $first_position->id;
                    }else{
                        $new_position = Position::create([
                            'user_id' => auth()->id(),
                            'title' => 'Не Указано',
                            'role' => 'default'
                        ]);
                        $pos_id = $new_position->id;
                    }
                    if($db_item && $item['id'] != $pos_id){

                        Item::where('user_id',auth()->id())->where('position_id',$item['id'])->update([
                            'position_id' => $pos_id
                        ]);
                        $db_item->delete();
                    }

                }
                else{
                    if (Arr::exists($item,'title')){
                        Position::where('user_id',auth()->id())->where('id',$item['id'])->update([
                            'title' => $item['title']
                        ]);
                    }

                }

            }
        }

        if($request->new_items){
            foreach ($request->new_items as $new_item){
                $item = Position::create([
                    'title' => $new_item['title'],
                    'user_id' => auth()->id()
                ]);
            }
        }
        return back()->with('success','Сохранено');
    }

    public function getCost(){
        return Cost::find(request()->id);
    }

    public function deleteCostFile()
    {
        $data = request()->all();
        //$data['queryId'], $data['file'], $data['type']
        $result = [
            'success' => false,
            'alertMessage' => 'Неудалось удалить файл'
        ];
        $cost = Cost::find($data['id']);

        if (!empty($cost->{$data['type']})) {
            $files = [];
            foreach ($cost->{$data['type']} as $file) {
                if ($file != $data['file']) {
                    $files[] = $file;
                }
            }
            $cost->{$data['type']} = $files;
        }

        if ($cost->save()) {
            Storage::delete('public/' . $data['file']);
            $result = [
                'success' => true,
                'alertMessage' => 'Файл удален'
            ];
        }

        return $result;
    }
}
