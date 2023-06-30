<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WorkerPostController extends Controller
{
    public function index(){
        $bases = Post::get();
        return view('pages.posts.edit',['bases' => $bases]);
    }

    public function update(Request $request){
        if($request->items){
            foreach ($request->items as $item){
                if(Arr::exists($item,'deleted')){
                    $db_item = Post::where('id',$item['id'])->first();
                    if($db_item){
                        $db_item->delete();
                    }
                }
                else{
                    if (Arr::exists($item,'title')){
                        Post::where('id',$item['id'])->update([
                            'title' => $item['title']
                        ]);
                    }

                }

            }
        }

        if($request->new_items){
            foreach ($request->new_items as $new_item){
                $item = Post::create([
                    'title' => $new_item['title'],
                ]);
            }
        }
        return back()->with('success','Сохранено');
    }
}
