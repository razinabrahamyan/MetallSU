<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\ItemStorage;
use App\Models\StoreItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index(){
        $storages = ItemStorage::where('user_id',auth()->id())->get();
        return view('pages.storage.index',['storages' => $storages]);
    }

    public function createStorage(Request $request){
        $storage = ItemStorage::create([
            'user_id' =>auth()->id(),
            'title' => $request->title
        ]);

        return redirect()->route('storage.show',$storage->id);
    }

    public function addItem(Request $request){
        if($request->product){
            $item = StoreItem::find($request->product);
            $item->count += $request->count;
            $item->save();
        }elseif($request->product_new){
            $item = StoreItem::create([
                'storage_id' => $request->storage_id,
                'count' => $request->count,
                'price' => $request->price,
                'name' => $request->product_new,
                'description' => $request->description
            ]);
        }
        Deduction::create([
            'item_id' => $item->id,
            'storage_id' => $request->storage_id,
            'date' => Carbon::parse($request->date)->format('Y-m-d H:i:s') ,
            'count' => $request->count,
            'price' => $request->price,
            'type' => 'addition'
        ]);
        return back()->with('success','Добавлено Успешно');
    }

    public function deductItem(Request $request){
        $store_item = StoreItem::find($request->product_id);
        $store_item->count -= $request->count;
        if($store_item->count >= 0){
            $store_item->save();
            Deduction::create([
                'to' => $request->to,
                'comment' => $request->comment,
                'item_id' => $request->product_id,
                'storage_id' => $request->storage_id,
                'date' => Carbon::parse($request->date)->format('Y-m-d H:i:s') ,
                'count' => $request->count,
                'type' => 'deduction'
            ]);
            return back()->with('success','Вычтено Успешно');
        }
        return back()->with('success','Что то пошло не так');

    }

    public function show($id){
        $storage = ItemStorage::where('id',$id)->with(['items','availableItems'])->first();
        if ($storage){
            $deductions = Deduction::where('storage_id',$storage->id)
                ->where(function ($query){
                    $query->where('type','deduction')
                        ->orWhereNull('type');
                })->orderByDesc('created_at')->paginate(10);

            return view('pages.storage.show',['storage' => $storage,'deductions' => $deductions]);
        }
        return redirect()->route('storage.index');
    }

    public function editDeductItemAjax(Request $request){
        $deduction = Deduction::find($request->deduct_id);
        $product = StoreItem::find($deduction->item_id);
        $old_deduction_count = $deduction->count;
        $new_deduction_count = $request->count;
        $product->count += $old_deduction_count - $new_deduction_count;
        $product->save();
        $deduction->count = $request->count;
        $deduction->to = $request->to;
        $deduction->comment = $request->comment;
        $deduction->save();
        return response()->json([
            'success' => 'success',
            'message' => 'Обновлено успешно',
            'item' => $deduction,
            'product' => $product
        ]);
    }

    public function editDeductItem($id){
        $deduction = Deduction::find($id);

        if($deduction && ($deduction->type === 'deduction' || $deduction->type === null) ){
            $storage = ItemStorage::find($deduction->storage_id);
            if($storage)
            return view('pages.storage.edit',['deduction' => $deduction ,'storage' => $storage]);
        }
        return redirect()->route('index');

    }

    public function editStorageItem($id){
        $item = StoreItem::where('id',$id)->first();

        if($item && $item->storage->user_id === auth()->id()) {
            $deductions = Deduction::where('item_id',$item->id)
                ->where('type','addition')->orderByDesc('created_at')->paginate(10);
            $storage = ItemStorage::find($item->storage_id);
            return view('pages.storage.edit_item', ['item' => $item, 'storage' => $storage , 'deductions' => $deductions]);
        }
        return redirect()->route('index');
    }
    public function updateDeductItem(Request $request){
        $deduction = Deduction::find($request->deduct_id);
        if($deduction && $deduction->user_id === auth()->id()){
            $storage = ItemStorage::find($deduction->storage_id);
            $product = StoreItem::find($deduction->item_id);

            $old_deduction_count = $deduction->count;
            $new_deduction_count = $request->count;

            $product->count += $old_deduction_count - $new_deduction_count;

            $deduction->count = $request->count;
            $deduction->to = $request->to;
            $deduction->comment = $request->comment;
            if($product->count >= 0){
                $product->save();
                $deduction->save();
                return redirect()->route('storage.show',$storage->id)->with('success','Обновлено успешно');
            }
            return redirect()->route('storage.show',$storage->id)->with('success','Что то пошло не так');
        }
        return redirect()->route('index');

    }

    public function updateStorageItem(Request $request){
        $item = StoreItem::find($request->item_id);
        if($item && $item->storage->user_id === auth()->id() && $request->name){
            $item->name = $request->name;
            $item->description = $request->comment;
            $item->price = $request->price;
            $item->save();
            return redirect()->route('storage.show',$item->storage->id);
        }
        return redirect()->route('index');
    }

    public function searchDeduction(Request $request){
        $deductions = Deduction::where('storage_id',$request->storage_id)
            ->where(function ($query){
                $query->where('type','deduction')
                    ->orWhereNull('type');
            })
            ->with('item')
            ->where(function ($query) use ($request){
                $query->whereHas('item',function ($it) use ($request){
                    return $it->where('name', 'like', '%'.$request->text.'%');
                })
                    ->orWhere('to', 'like', '%'.$request->text.'%')
                    ->orWhere('comment', 'like', '%'.$request->text.'%')
                    ->orWhere('count', 'like', '%'.$request->text.'%')
                    ->orWhere('date', 'like', '%'.$request->text.'%');
            })->orderByDesc('created_at')->get();
        $deductions->map(function ($value){
            $value->date = Carbon::parse($value->date)->format('d/m/Y');
        });
        return response()->json([
            'success' => 'success',
            'data' => $deductions
        ]);
    }

    public function searchStoreItem(Request $request){
        $items = StoreItem::where('storage_id',$request->storage_id)
            ->where(function ($query) use ($request){
                $query
                    ->where('name', 'like', '%'.$request->text.'%')
                    ->orWhere('description', 'like', '%'.$request->text.'%')
                    ->orWhere('count', 'like', '%'.$request->text.'%');
            })->orderByDesc('created_at')->get();
        return response()->json([
            'success' => 'success',
            'data' => $items
        ]);
    }


    public function toCalculationPage($id){
        $storage = ItemStorage::where('id',$id)->first();
        if($storage && $storage->user_id === auth()->id()){
            return view('pages.storage.calculate',['storage' => $storage]);
        }

        return redirect()->route('index');

    }

    public function toHistoryPage($id){
        $storage = ItemStorage::where('id',$id)->first();
        $history = Deduction::where('storage_id',$id);
        if(session('history') === 'deduction'){
            $history = $history->where(function ($query){
                $query->where('type','deduction')
                    ->orWhereNull('type');
            });
        }elseif (session('history') === 'addition'){
            $history = $history->where('type','addition');
        }
        $history = $history->orderByDesc('created_at')->paginate(20);
        if($storage && $storage->user_id === auth()->id()){
            return view('pages.storage.history',['storage' => $storage,'history' => $history]);
        }
        return redirect()->route('index');
    }


    public function getStorageData(Request $request){
        $message = '';
        $message.= $request->item? 'предмет - '.$request->item.', ' :'';
        $message.= $request->count? 'количество - '.$request->count.', ' :'';
        $message.= $request->who? 'кому - '.$request->who.', ' :'';
        $message.= $request->date_from? 'дата от - '.$request->date_from.', ' :'';
        $message.= $request->date_to? 'до - '.$request->date_to.', ' :'';
        $message.= $request->comment? 'комментарий - '.$request->comment :'';

        $itog = [];
        $calculations = [];
        if($request->item || $request->count || $request->who || $request->date_from || $request->date_to || $request->comment){
            $deductions = Deduction::where('storage_id',$request->storage_id)
                ->with('item');
            if($request->item){
                $deductions = $deductions->whereHas('item',function ($item) use($request){
                    return $item->where('name', 'like', '%'.$request->item.'%');
                });
            }
            if($request->count){
                $deductions = $deductions->where('count', 'like', $request->count.'%');
            }
            if($request->who){
                $deductions = $deductions->where('to', 'like', $request->who.'%');
            }
            if($request->comment){
                $deductions = $deductions->where('comment', 'like', '%'.$request->comment.'%');
            }
            if($request->date_from){
                $deductions = $deductions->where('date','>=' ,Carbon::parse($request->date_from)->format('Y-m-d'));
            }
            if($request->date_to){
                $deductions = $deductions->where('date', '<=',Carbon::parse($request->date_to)->format('Y-m-d'));
            }
            $deductions = $deductions->orderByDesc('created_at')->get();

            $total_sum = 0;
            if(count($deductions)){
                $calculations = $deductions->groupBy(function($gr){
                    return $gr->item->name.'('.$gr->item->description.')';
                });
                foreach ($calculations as $key => $calculation){
                    $total_sum += $calculation->sum('count')*$calculation->first()->item->price;
                    $itog[] = [
                        'key' => $key,
                        'value' => $calculation->sum('count')*$calculation->first()->item->price,
                        'count' => $calculation->sum('count')
                    ];
                }
            }



            return response()->json([
                'success' => 'success',
                'data' => $deductions,
                'message' => $message,
                'calculations' => $calculations,
                'itog' => $itog,
                'total_sum' => $total_sum,
                'request' => $request->all()
            ]);
        }

        $message = '';
        $message.= $request->item? 'предмет - '.$request->item.', ' :'';
        $message.= $request->count? 'количество - '.$request->count.', ' :'';
        $message.= $request->who? 'кому - '.$request->who.', ' :'';
        $message.= $request->date? 'дата - '.$request->date.', ' :'';
        $message.= $request->comment? 'комментарий - '.$request->comment :'';

        return response()->json([
            'success' => 'success',
            'data' => [],
            'message' => $message,
            'calculations' => [],
            'itog' => [],
            'total_sum' => null
        ]);
    }

    public function setHistoryFilter(Request $request){
        if($request->filter === 'deduct'){
            session()->put('history','deduction');
        }elseif ($request->filter === 'add'){
            session()->put('history','addition');
        }elseif ($request->filter === 'all'){
            session()->put('history','all');
        }
        return response()->json([
            'success' => 'success',
            'message' => 'filter set'
        ]);
    }
}
