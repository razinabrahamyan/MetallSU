<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\Item;
use App\Models\ItemStorage;
use App\Models\Responsible;
use App\Models\ResponsibleType;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResponsibleController extends Controller
{

    public function getTreeInfo(Request $request){
        $statistics = ResponsibleType::where('user_id',auth()->id())->with(['responsibles' => function($responsibles) use($request){
            $responsibles->with(['costs' => function($costs) use($request){
                $costs->when($request->from,function ($query) use ($request){
                    $query->whereDate('date','>=',Carbon::parse($request->from)->format('Y-m-d'));
                })
                    ->when($request->to,function ($query) use ($request){
                        $query->whereDate('date','<=',Carbon::parse($request->to)->format('Y-m-d'));
                    });
            }]);
        }])->get();
        foreach ($statistics as $statistic){
            $sum = 0;
            foreach ($statistic->responsibles as $responsible){
                $responsible->done_costs = $this->makeMoney($responsible->costs->sum('value'));
                $sum += $responsible->costs->sum('value');
            }
            $statistic->done_costs = $this->makeMoney($sum);
        }
        return response()->json([
            'success' => 'success',
            'data' => $statistics
        ]);
    }

    public function makeMoney($money){
        $string = strval($money);
        if(!$string){
            $string = '0';
        }
        $result = '';
        $check = true;
        while($check){
            if(strlen($string)>3){
                $result = ','.substr($string, -3).$result;
                $string = substr($string,0, -3);
            }else{
                $result = $string.$result;
                $check = false;
            }
        }
        if(strlen($result)){
            $result .= '₽';
        }
        return $result;
    }

    public function responsibleIndex($id){
        $responsible = Responsible::where('id',$id)->where('user_id',auth()->id())->with(['costs' => function($cost){
            $cost->where('user_id',auth()->id())->with(['item' => function($item){
                $item->with('parentItem');
            },'responsibles' => function($responsible){
                $responsible->with('type');
            }])->orderBy('date','desc')->orderBy('created_at', 'DESC');
        }])->first();

        if($responsible){
            $statistics = ResponsibleType::where('user_id',auth()->id())->where('id','!=',$responsible->type_id)->with(['responsibles' => function($responsibles){
                $responsibles->with('costs');
            }])->get();
            $additionalColumns = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->where('id','!=',$responsible->type_id)->get();
            foreach ($responsible->costs as $cost){
                $cost->responsibleValues = $cost->responsibles->groupBy('type_id');
                $cost->formatted_value = $this->makeMoney($cost->value);
            }
            $chart_costs = $responsible->costs->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });
            $round_chart_costs = $responsible->costs->groupBy(function ($cost) {
                if($cost->item->parent_id){
                    return $cost->item->parentItem->title.'(Группа)';
                }
                return $cost->item->title;
            });
            $chart_data = [];
            $round_chart_data = [];
            $values = [];
            foreach ($chart_costs as $key => $chart_cost){
                $new_item = [];
                $new_item['x'] = $key;
                $new_item['y'] = $chart_cost->sum('value');
                $chart_data[] = $new_item;
            }
            foreach ($round_chart_costs as $key => $chart_cost){
                $new_item = [];
                $new_item['key'] = $key;
                $new_item['value'] = $chart_cost->sum('value');
                $round_chart_data[] = $new_item;
            }
            $items = Item::where('parent_id',null)->where('user_id',auth()->id())->with(['defaultResponsibles','subItems' => function($subitem){
                $subitem->with('defaultResponsibles');
            }])->orderBy('id','DESC')->get();

            return view('pages.statistic.index',[
                'responsible' => $responsible,
                'costs' => $responsible->costs,
                'additionalColumns' => $additionalColumns,
                'chart_costs' => $chart_costs,
                'chart_data' => $chart_data,
                'statistics' => $statistics,
                'round_chart_costs' => $round_chart_data,
                'items' => $items
            ]);
        }
        return redirect()->route('index');
    }

    public function getResponsiblesRows(Request $request){
        $addit_arr = [];
        foreach ($request->additionals as $additional_id){
            if($additional_id){
                array_push($addit_arr,intval($additional_id));
            }
        }
        $requestedResponsible = Responsible::where('id',$request->responsible)->first();
        $costs = Cost::where('user_id',auth()->id())
            ->whereHas('responsibles',function ($item) use ($request){
                $item->where ('responsibles.id',$request->responsible);
            })
            ->with(['item' => function($item){
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
        $additionals = ResponsibleType::where('user_id',auth()->id())->where('id','!=',$requestedResponsible->type_id)->orderBy('order')->get();
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
                array_push($arr_item,'<span class="should_money cashless">'. $this->makeMoney($cost->value) .'</span>');
            }else{
                array_push($arr_item,'<span class="should_money">'. $this->makeMoney($cost->value) .'</span>');
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
            $lil_arr['value'] = $this->makeMoney($grouped_item_cost->sum('value'));
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

        $sum = $costs->sum('value');
        $cashless = $costs->where('cashless','1')->sum('value');
        $cash = $this->makeMoney($sum - $cashless);
        $sum = $this->makeMoney($sum);
        $cashless = $this->makeMoney($cashless);
        $count = $costs->sum('count');
        return response()->json([
            'success' => 'success',
            'data' => $request->all(),
            'costs' => $data_array,
            'edit_position' => 5 + count($additionals),
            'sum' => $sum,
            'cash' => $cash,
            'cashless' => $cashless,
            'count' => $count,
            'split_item_arr' => $split_item_arr,
            'show_filters' => $filter_chosen_string
        ]);
    }
    public function getCategoryRows(Request $request){
        $addit_arr = [];
        foreach ($request->additionals as $additional_id){
            if($additional_id){
                array_push($addit_arr,intval($additional_id));
            }
        }
        $allowedItems = Item::where('parent_id',$request->category)->get()->pluck('id');
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
            },function ($query) use($allowedItems){
                return $query->whereIn('item_id',$allowedItems);
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
                array_push($arr_item,'<span class="should_money cashless">'. $this->makeMoney($cost->value) .'</span>');
            }else{
                array_push($arr_item,'<span class="should_money">'. $this->makeMoney($cost->value) .'</span>');
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
            $lil_arr['value'] = $this->makeMoney($grouped_item_cost->sum('value'));
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

        $sum = $costs->sum('value');
        $cashless = $costs->where('cashless','1')->sum('value');
        $cash = $this->makeMoney($sum - $cashless);
        $sum = $this->makeMoney($sum);
        $cashless = $this->makeMoney($cashless);
        $count = $costs->sum('count');
        return response()->json([
            'success' => 'success',
            'data' => $request->all(),
            'costs' => $data_array,
            'edit_position' => 5 + count($additionals),
            'sum' => $sum,
            'count' => $count,
            'split_item_arr' => $split_item_arr,
            'cashless' => $cashless,
            'cash' => $cash,
            'show_filters' => $filter_chosen_string
        ]);
    }
    public function getItemRows(Request $request){
        $addit_arr = [];
        foreach ($request->additionals as $additional_id){
            if($additional_id){
                array_push($addit_arr,intval($additional_id));
            }
        }
        $costs = Cost::where('item_id',$request->item)->where('user_id',auth()->id())->with(['item' => function($item){
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
            array_push($arr_item,$cost->count);
            if($cost->cashless === '1'){
                array_push($arr_item,'<span class="should_money cashless">'. $this->makeMoney($cost->value) .'</span>');
            }else{
                array_push($arr_item,'<span class="should_money">'. $this->makeMoney($cost->value) .'</span>');
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
            $lil_arr['value'] = $this->makeMoney($grouped_item_cost->sum('value'));
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

        $sum = $costs->sum('value');
        $cashless = $costs->where('cashless','1')->sum('value');
        $cash = $this->makeMoney($sum - $cashless);
        $sum = $this->makeMoney($sum);
        $cashless = $this->makeMoney($cashless);
        $count = $costs->sum('count');
        return response()->json([
            'success' => 'success',
            'data' => $request->all(),
            'costs' => $data_array,
            'edit_position' => 5 + count($additionals),
            'sum' => $sum,
            'cash' => $cash,
            'cashless' => $cashless,
            'count' => $count,
            'split_item_arr' => $split_item_arr,
            'show_filters' => $filter_chosen_string
        ]);
    }
    public function itemsIndex($id){
        $item = Item::where('id',$id)->where('user_id',auth()->id())->with(['subItems','costs' => function($cost){
            $cost->where('user_id',auth()->id())->with(['item' => function($item){
                $item->with('parentItem');
            },'responsibles' => function($responsible){
                $responsible->with('type');
            }])->orderBy('date','DESC')->orderBy('created_at', 'DESC');
        }])->first();
        if($item){
            $statistics = ResponsibleType::where('user_id',auth()->id())->with(['responsibles' => function($responsibles){
                $responsibles->with('costs');
            }])->get();
            if(count($item->subItems)){
                return redirect()->route('category.statistic',$id);
            }else{
                $additionalColumns = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->get();
                foreach ($item->costs as $cost){
                    $cost->responsibleValues = $cost->responsibles->groupBy('type_id');
                    $cost->formatted_value = $this->makeMoney($cost->value);
                }
                $chart_costs = $item->costs->groupBy(function ($item) {
                    return Carbon::parse($item->date)->format('Y-m-d');
                });
                $chart_data = [];

                $values = [];
                foreach ($chart_costs as $key => $chart_cost){
                    $new_item = [];
                    $new_item['x'] = $key;
                    $new_item['y'] = $chart_cost->sum('value');
                    $chart_data[] = $new_item;
                }

                return view('pages.statistic.item',[
                    'item' => $item,
                    'additionalColumns' => $additionalColumns,
                    'statistics' => $statistics,
                    'chart_costs' => $chart_costs,
                    'chart_data' => $chart_data,
                ]);
            }
        }
        return redirect()->route('index');

    }

    public function categoryIndex($id){
        $item = Item::where('id',$id)->where('user_id',auth()->id())->with(['subItems'])->first();
        if($item && count($item->subItems)){
            $statistics = ResponsibleType::where('user_id',auth()->id())->with(['responsibles' => function($responsibles){
                $responsibles->with('costs');
            }])->get();
            $costs = Cost::whereIn('item_id',$item->subItems->pluck('id'))->where('user_id',auth()->id())->with(['item' => function($item){
                $item->with('parentItem');
            },'responsibles' => function($responsible){
                $responsible->with('type');
            }])->orderBy('date','desc')->orderBy('created_at', 'DESC')->get();
            $additionalColumns = ResponsibleType::where('user_id',auth()->id())->orderBy('order')->get();
            foreach ($costs as $cost){
                $cost->responsibleValues = $cost->responsibles->groupBy('type_id');
                $cost->formatted_value = $this->makeMoney($cost->value);
            }
            $chart_costs = $costs->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });
            $chart_data = [];

            $values = [];
            foreach ($chart_costs as $key => $chart_cost){
                $new_item = [];
                $new_item['x'] = $key;
                $new_item['y'] = $chart_cost->sum('value');
                $chart_data[] = $new_item;
            }

            $round_chart_costs = $costs->groupBy(function ($cost) {
                return $cost->item->title;
            });
            $round_chart_data = [];

            foreach ($round_chart_costs as $key => $chart_cost){
                $new_item = [];
                $new_item['key'] = $key;
                $new_item['value'] = $chart_cost->sum('value');
                $round_chart_data[] = $new_item;
            }


            return view('pages.statistic.category',[
                'item' => $item,
                'costs' => $costs,
                'additionalColumns' => $additionalColumns,
                'chart_costs' => $chart_costs,
                'chart_data' => $chart_data,
                'statistics' => $statistics,
                'round_chart_costs' => $round_chart_data
            ]);
        }

        return redirect()->route('index');

    }

    public function mainSearch(Request $request){

        if(auth()->user()->role_id === 1){
            $responsibles = Responsible::where('user_id',auth()->id())->with('type')->where('name', 'like', '%'.$request->text.'%')->orderBy('type_id')->limit(30)->get();
            $items = Item::where('user_id',auth()->id())->with('parentItem')->where('title', 'like', '%'.$request->text.'%')->limit(10)->get();
            $workers = Worker::where('name', 'like', '%'.$request->text.'%')->limit(10)->get();
            $link = 'responsible';
            return response()->json([
                'success' => 'success',
                'responsibles'  => $responsibles,
                'items' => $items,
                'workers' => $workers,
                'link' => $link
            ]);
        }else{
            $tables = ItemStorage::where('title', 'like', '%'.$request->text.'%')->where('user_id', auth()->id())->get();
            $link = 'store';
            return response()->json([
                'success' => 'success',
                'tables'  => $tables,
                'link' => $link
            ]);
        }
    }
}
