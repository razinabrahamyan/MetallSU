<?php


namespace App\Http\Controllers;

use App\Models\Responsible;
use App\Models\ResponsibleType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PositionController extends Controller
{
    public function lists(){
        $bases = ResponsibleType::where('user_id',auth()->id())->with(['responsibles' => function($responsible){
            $responsible->with('costs');
        }])->get();
        return view('pages.list.index',['bases' => $bases]);
    }
    public function listEdit($id){
        $base = ResponsibleType::where('user_id',auth()->id())->where('id',$id)->with('responsibles')->first();
        if($base){
            $base->orderedResponsible = $base->responsibles->sortBy(function ($responsible){
               return $responsible->costs->sum('value');
            },0,true);
            return view('pages.list.edit',['base' => $base]);
        }
        return redirect()->route('index');
    }

    public function listUpdate(Request $request){
        if($request->items){
            foreach ($request->items as $item){
                if (Arr::exists($item,'deleted')){
                    Responsible::where('user_id',auth()->id())->where('id',$item['id'])->delete();
                }
                elseif (Arr::exists($item,'title')){
                    Responsible::where('user_id',auth()->id())->where('id',$item['id'])->update([
                        'name' => $item['title']
                    ]);
                }

            }
        }
        if($request->new_items){
            foreach ($request->new_items as $new_item){
                $item = Responsible::create([
                    'name' => $new_item['title'],
                    'type_id' => $request->base,
                    'user_id' => auth()->id()
                ]);
            }
        }
        return redirect()->route('lists.index')->with('success','Сохранено');
    }

    public function listAdd(Request $request){
        $base_type =  ResponsibleType::create([
            'name' => $request->name,
            'user_id' => auth()->id()
        ]);
        return redirect()->route('lists.edit',$base_type->id);
    }

}
