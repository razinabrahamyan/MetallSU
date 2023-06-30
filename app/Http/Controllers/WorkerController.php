<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\Category;
use App\Models\Post;
use App\Models\SalaryIncrease;
use App\Models\Worker;
use App\Models\WorkerEvent;
use App\Services\WorkersLazyLoadService;
use App\Traits\WorkerInfoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class WorkerController extends Controller
{
    use WorkerInfoTrait;
    public function index(){
        $first_base = Base::where('user_id',auth()->id())->first();
        if(!$first_base){
            return redirect()->route('edit.bases.page');
        }
        return view('pages.bases.category.edit');
    }
    public function firing(Request $request){
        if($request->date){
            WorkerEvent::create([
                'worker_id' => $request->worker_id,
                'date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null,
                'type_id' => 2,
                'additional' => ['comment' => $request->comment]
            ]);
        }
        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = (new Worker())->fullPaket()->where('id',$request->worker_id)->first();
        $history = $this->getWorkerHistory($worker_all);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'data' => $request->all()
        ]);
    }
    public function updateFiring(Request $request){
        if($request->deleted){
            WorkerEvent::where('id',$request->id)->delete();
        }else if($request->date && $request->id){
            WorkerEvent::where('id',$request->id)->update([
                'date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null,
                'additional->comment' => $request->comment
            ]);
        }

        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = (new Worker())->fullPaket()->where('id',$request->worker_id)->first();
        $history = $this->getWorkerHistory($worker_all);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'data' => $request->all()
        ]);
    }
    public function reEmploy(Request $request){
        if($request->date){
            WorkerEvent::create([
                'worker_id' => $request->worker_id,
                'date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null,
                'type_id' => 1,
                'additional' => ['comment' => $request->comment, 'type' => 're_employ']
            ]);
        }
        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = (new Worker())->fullPaket()->where('id',$request->worker_id)->first();
        $history = $this->getWorkerHistory($worker_all);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'data' => $request->all()
        ]);
    }
    public function updateReEmploy(Request $request){
        if($request->deleted){
            WorkerEvent::where('id',$request->id)->delete();
        }else if($request->date && $request->id){
            $event = WorkerEvent::where('id',$request->id)->first();
            if($event){
                $event->date = $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null;
                $event->additional->comment = $request->comment;
                if($event->additional->type === 'hiring'){
                    SalaryIncrease::where('worker_id',$event->worker_id)->where('initial',true)->update([
                        'date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null
                    ]);
                }
                $event->save();
            }
        }

        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = (new Worker())->fullPaket()->where('id',$request->worker_id)->first();
        $history = $this->getWorkerHistory($worker_all);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'data' => $request->all()
        ]);
    }
    public function categoryChange(Request $request){

        if($request->date && $request->category){
            $worker_check = Worker::find($request->worker_id);

            if($worker_check->category_id != $request->category){
                $category = Category::where('id',$request->category)->with('base')->first();
                if($category){
                    WorkerEvent::create([
                        'worker_id' => $request->worker_id,
                        'type_id' => 3,
                        'date' =>$request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null,
                        'additional' => [
                            'from' => $worker_check->category->base->title.'/'.$worker_check->category->title,
                            'to' => $category->base->title.'/'.$category->title,
                            'new_id' => $category->id,
                            'old_id' => $worker_check->category_id
                        ]
                    ]);
                    $worker_check->category_id = $request->category;
                }
            }
            $worker_check->save();
        }
        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = Worker::where('id',$request->worker_id)->with(['events' ,'salaries','holidays','lastIncrease','increases','category' => function($category){
            $category->with('base');
        }])->first();
        $history = $this->getWorkerHistory($worker_all);
        $colleagues = $this->getWorkersColleagues($worker);

        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'colleagues' => $colleagues,
            'data' => $request->all()
        ]);
    }
    public function updateCategoryChange(Request $request){
        if($request->deleted){
            $worker_check = Worker::find($request->worker_id);
            $event = WorkerEvent::where('worker_id',$request->worker_id)->where('type_id',3)->orderBy('id','DESC')->first();
            if($event->id === $request->id){
                $worker_check->category_id = $event->additional->old_id;
            }
            WorkerEvent::where('id',$request->id)->delete();
            $worker_check->save();
        }else if($request->date && $request->category && $request->id){
            $worker_check = Worker::find($request->worker_id);
            $event = WorkerEvent::where('worker_id',$request->worker_id)->where('type_id',3)->orderBy('id','DESC')->first();
            if($event->id === $request->id){
                $worker_check->category_id = $request->category;
            }
            $category = Category::where('id',$request->category)->with('base')->first();
            $event->additional->to = $category->base->title.'/'.$category->title;
            $event->date = $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null;
            $worker_check->save();
            $event->save();
        }
        $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
        $worker_all = Worker::where('id',$request->worker_id)->with(['events' ,'salaries','holidays','lastIncrease','increases','category' => function($category){
            $category->with('base');
        }])->first();
        $history = $this->getWorkerHistory($worker_all);
        $colleagues = $this->getWorkersColleagues($worker);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'history' => $history,
            'colleagues' => $colleagues,
            'data' => $request->all()
        ]);
    }
    public function update(Request $request){
        $worker_check = Worker::where('id',$request->worker_id)->with(['category' => function($category){
            $category->with('base');
        }])->first();
        if($worker_check){
            $worker_check->name = $request->name;
            $worker_check->official_salary = $request->official_salary;
            $worker_check->post_id = $request->post_id;
            $worker_check->additional = $request->additional;
            SalaryIncrease::where('id',$request->last_increase_id)->update([
                'new_value' => $request->salary
            ]);
            $worker_check->save();
            $worker = (new Worker(now()))->fullPaket()->where('id',$request->worker_id)->first();
            $worker_all = Worker::where('id',$request->worker_id)->with(['events' ,'salaries','holidays','lastIncrease','increases','category' => function($category){
                $category->with('base');
            }])->first();
            $history = $this->getWorkerHistory($worker_all);
            return response()->json([
                'success' => 'success',
                'worker' => $worker,
                'worker_all' => $worker_all,
                'history' => $history,
                'data' => $request->all()
            ]);
        }
        return response()->json([
            'success' => 'error',
            'data' => $request->all()
        ]);
    }
    public function getSelectBases(){
        $bases = Base::where('user_id', auth()->id())->with('categories')->get();
        return response()->json([
            'success' => 'success',
            'bases' => $bases
        ]);
    }

    public function workersLazyLoad(){

        return (new WorkersLazyLoadService())->prepareTableParams(request())
            ->prepareQueries()
            ->initLazyLoad();
    }
    public function getWorkerData($id){
        $worker = (new Worker(now()))->fullPaket()->where('id',$id)->first();
        $worker_all = (new Worker())->fullPaket()->where('id',$id)->first();
        $categories = Category::with('base')->orderBy('base_id')->get();
        $posts = Post::get();
        $salaries = $worker_all->salaries;
        foreach ($salaries as $salary){
            $salary->show_date = Carbon::parse($salary->for)->format('d.m.Y');
        }
        $salaries = array_values($salaries->sortByDesc('for')->toArray());;

        $history = $this->getWorkerHistory($worker_all);
        $colleagues = $this->getWorkersColleagues($worker);
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'worker_all' => $worker_all,
            'salaries' => $salaries,
            'history' => $history,
            'colleagues' => $colleagues,
            'categories' => $categories,
            'posts' => $posts,
        ]);
    }

    public function addWorker(Request $request){
        if($request->category_id && $request->name){
            $new_worker = new Worker();
            $new_worker->category_id = $request->category_id;
            $new_worker->name = $request->name;
            $new_worker->salary = $request->salary;
            $new_worker->official_salary = $request->official_salary;
            $new_worker->additional = $request->additional;
            $new_worker->post_id = $request->post_id;
            $new_worker->user_id = auth()->id();
            $new_worker->save();

            $new_worker->events()->create([
                'date' => Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'),
                'type_id' => 1,
                'additional' =>['type' => 'hiring','comment' => $request->additional]
            ]);

            $new_worker->increases()->create([
                'date' => Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'),
                'new_value' => $request->salary,
                'initial' => true
            ]);
            $worker = (new Worker($request->main_date))->fullPaket()->where('id',$new_worker->id)->first();
            return response()->json([
                'success' => 'success',
                'data' => $request->all(),
                'worker' => $worker,
            ]);
        }

    }
    public function toWorkerAddPage(){
        $first_base = Base::where('user_id',auth()->id())->first();
        if(!$first_base){
            return redirect()->route('edit.bases.page');
        }else{
            $category = Category::whereHas('base',function ($query){
                $query->where('user_id',auth()->id());
            })->first();
            if(!$category){
                return redirect()->route('categories.index');
            }
        }
        return view('pages.workers.create');
    }
    public function toBasesEditPage(){
        $bases = Base::where('user_id',auth()->id())->get();
        return view('pages.bases.edit',['bases' => $bases]);
    }

    public function getTabItems(){
        $id = null;
        if(session()->get('current_base_tag')){
            $id = session()->get('current_base_tag');
        }else{
            $id = Base::where('user_id',auth()->id())->first()->id;
        }
        session()->put('current_base_tag',$id);
        $bases = Base::where('user_id',auth()->id())
            ->with(['categories' => function($category){
                $category->with(['workers' => function($worker){
                    $worker->orderBy('id','DESC');
                }])->orderBy('id','DESC');
            }])->select('id','title')->get();
        return response()->json([
            'success' => 'success',
            'bases' => $bases,
            'active_tab' => session()->get('current_base_tag')
        ]);
    }

    public function setActiveTab($tab_id){
        session()->put('current_base_tag',$tab_id);
        return response()->json([
            'success' => 'success'
        ]);
    }

    public function updateCategories(Request $request){
        if($request->categories){
            foreach ($request->categories as $category){
                if($category['id']){
                    Category::where('id',$category['id'])->update([
                        'title' => $category['title']
                    ]);
                }else{
                    Category::create([
                        'title' => $category['title'],
                        'base_id' => $category['base_id'],
                    ]);
                }
            }
        }
        $bases = Base::where('user_id',auth()->id())
            ->with(['categories' => function($category){
                $category->with(['workers' => function($worker){
                    $worker->orderBy('id','DESC');
                }])->orderBy('id','DESC');
            }])->select('id','title')->get();

        return response()->json([
            'success' => 'success',
            'bases' => $bases,
            'active_tab' => session()->get('current_base_tag')
        ]);
    }
    public function updateBases(Request $request){
        if($request->items){
            foreach ($request->items as $item){
                if(Arr::exists($item,'deleted')){
                    $db_item = Base::where('user_id',auth()->id())->where('id',$item['id'])->first();
                    if($db_item){
                        Category::where('base_id',$item['id'])->update([
                            'base_id' => null
                        ]);
                        $db_item->delete();
                    }
                }
                else{
                    if (Arr::exists($item,'title')){
                        Base::where('user_id',auth()->id())->where('id',$item['id'])->update([
                            'title' => $item['title']
                        ]);
                    }

                }

            }
        }

        if($request->new_items){
            foreach ($request->new_items as $new_item){
                $item = Base::create([
                    'title' => $new_item['title'],
                    'user_id' => auth()->id()
                ]);
            }
        }
        return back()->with('success','Сохранено');
    }

    public function homepage(){
        return view('pages.workers.homepage');
    }
}
