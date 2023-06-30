<?php

namespace App\Http\Controllers;

use App\Exports\FullExport;
use App\Models\ExportGroup;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class OutcomeController extends Controller
{
    public function index(){
        return view('pages.statistic.outcome');
    }

    public function search(Request $request){
        $items = Item::where('user_id',auth()->id())->with('parentItem')->where('title', 'like', '%'.$request->text.'%')->limit(10)->get();
        return response()->json([
            'success' => 'success',
            'items' => $items,
        ]);
    }

    public function export(Request $request){
        $file_type = '';
        switch ($request->file_type){
            case 'excel':
                $file_type = 'xlsx';
                break;
            case 'pdf':
                $file_type = 'pdf';
                break;
            case 'html':
                $file_type = 'html';
                break;
            default :$file_type = 'invalid';
        }
        if($file_type !== 'invalid'){
            $file_name = $request->file_name ? : 'Итог';
            $file_name.= '.'.$file_type;

            return Excel::download(new FullExport($request), $file_name);
        }
        return redirect()->route('index');


    }

    public function toExportGroupEditPage(){
        $positions = ExportGroup::where('user_id',auth()->id())->with(['items' => function($item){
            $item->with(['parentItem'=>function($parentItem){
                $parentItem->with('position');
            },'position'])->orderBy('parent_id');
        }])->get();
        return view('pages.export_group.edit',['positions' => $positions]);
    }

    public function updateExportGroups(Request $request){
        if($request->items){
            foreach ($request->items as $item){
                if(Arr::exists($item,'deleted')){
                    $db_item = ExportGroup::where('user_id',auth()->id())->where('id',$item['id'])->first();
                    if($db_item ){

                        Item::where('user_id',auth()->id())->where('group_id',$item['id'])->update([
                            'group_id' => null
                        ]);
                        $db_item->delete();
                    }

                }
                else{
                    if (Arr::exists($item,'title')){
                        ExportGroup::where('user_id',auth()->id())->where('id',$item['id'])->update([
                            'title' => $item['title']
                        ]);
                    }
                }
            }
        }
        if($request->new_items){
            foreach ($request->new_items as $new_item){
                $item = ExportGroup::create([
                    'title' => $new_item['title'],
                    'user_id' => auth()->id()
                ]);
            }
        }

        return back()->with('success','Сохранено');
    }
}
