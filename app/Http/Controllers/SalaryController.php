<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\Holiday;
use App\Models\Post;
use App\Models\Salary;
use App\Models\SalaryIncrease;
use App\Models\Worker;
use App\Models\WorkerEvent;
use App\Models\WorkerEventType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SalaryController extends Controller
{
    public function index(){
        return view('pages.salary.index');
    }

    public function pay(Request $request){

        if($request->salary || $request->bonus){
            $salary = Salary::create([
                'worker_id' => $request->worker_id,
                'amount' => $request->salary,
                'bonus' => $request->bonus,
                'for' => Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'),
                'additional' => $request->official ? ['official_salary' => true] : null,

            ]);
        }

        if($request->salaries){
            foreach ($request->salaries as $salary){
                if(Arr::exists($salary,'modified')){
                    if($salary['deleted_at']){
                        Salary::where('id',$salary['id'])->delete();
                    }else{
                        if($salary['amount'] || $salary['bonus']){
                            Salary::where('id',$salary['id'])->update([
                                'amount' => $salary['amount'],
                                'bonus' => $salary['bonus'],
                            ]);
                        }else{
                            Salary::where('id',$salary['id'])->delete();
                        }
                    }

                }
            }
        }
        $worker = (new Worker($request->main_date))->fullPaket()->where('id',$request->worker_id)->first();
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'data' => $request->all()
        ]);
    }



    public function holiday(Request $request){

        if($request->from){
            $holiday = Holiday::create([
                'worker_id' => $request->worker_id,
                'start_date' => $request->from ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->from,'UTC')->timezone('Europe/Moscow'): null ,
                'end_date' => $request->to ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->to,'UTC')->timezone('Europe/Moscow') : null,
            ]);
        }

        if($request->holidays){
            foreach ($request->holidays as $holiday){
                if(Arr::exists($holiday,'modified') && $holiday['type'] === 'holiday'){
                    Holiday::where('id',$holiday['id'])->update([
                        'start_date' => $holiday['start_date']? Carbon::createFromFormat('Y-m-d\TH:i:s+',$holiday['start_date'],'UTC')->timezone('Europe/Moscow'): null,
                        'end_date' => $holiday['end_date']? Carbon::createFromFormat('Y-m-d\TH:i:s+',$holiday['end_date'],'UTC')->timezone('Europe/Moscow'): null,
                        'paid' => $holiday['paid']
                    ]);
                }
            }
        }

        $worker = (new Worker($request->main_date))->fullPaket()->where('id',$request->worker_id)->first();
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'data' => $request->all()
        ]);
    }
    public function getWorkerHistory($worker_all){
        $events = $worker_all->events;
        $holidays = $worker_all->holidays;
        $increases = $worker_all->increases;
        foreach ($holidays as $holiday){
            $holiday->date = $holiday->start_date;
            $holiday->history_type = $holiday->type;
        }

        foreach ($events as $event){
            $event->history_type = 'event';
        }
        foreach ($increases as $increase){
            $increase->history_type = 'increase';
        }
        return array_values($events->concat($holidays)->concat($increases)->sortBy('date')->toArray());
    }
    public function salaryIncrease(Request $request){
        if($request->date){
            $last_increase = SalaryIncrease::where('worker_id',$request->worker_id)->orderBy('id','DESC')->first();
            SalaryIncrease::create([
                'worker_id' => $request->worker_id,
                'date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null ,
                'old_value' => $last_increase->new_value,
                'new_value' => $request->salary
            ]);
        }
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
    public function updateSalaryIncrease(Request $request){
        if($request->deleted && $request->id){
            $increase = SalaryIncrease::where('id',$request->id)->first();
            if(!$increase->initial){
                $increase->delete();
            }
        }else if($request->id){
            $increase = SalaryIncrease::where('id',$request->id)->first();
            $increase->new_value = $request->salary;
            if(!$increase->initial && $request->date){
                $increase->date = $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null ;
            }
            $increase->save();
        }
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

    public function dayOff(Request $request){
        if($request->date){
            $holiday = Holiday::create([
                'worker_id' => $request->worker_id,
                'start_date' => $request->date ? Carbon::createFromFormat('Y-m-d\TH:i:s+',$request->date,'UTC')->timezone('Europe/Moscow'): null ,
                'additional' => ['days' => $request->days],
                'type' => 'day_off'
            ]);
        }

        if($request->holidays){
            foreach ($request->holidays as $holiday){
                if(Arr::exists($holiday,'modified') && $holiday['type'] === 'day_off'){
                    Holiday::where('id',$holiday['id'])->update([
                        'start_date' => $holiday['start_date']? Carbon::createFromFormat('Y-m-d\TH:i:s+',$holiday['start_date'])->timezone('Europe/Moscow'): null,
                        'paid' => $holiday['paid'],
                        'additional' => $holiday['additional']
                    ]);
                }
            }
        }
        $worker = (new Worker($request->main_date))->fullPaket()->where('id',$request->worker_id)->first();
        return response()->json([
            'success' => 'success',
            'worker' => $worker,
            'data' => $request->all()
        ]);
    }

    public function getSelectBases($date){
        $year = $date ? Carbon::parse($date)->format('Y') : null;
        $month = $date ? Carbon::parse($date)->format('m') : null;
        $bases = Base::where('user_id', auth()->id())
            ->with(['categories' => function($category) use($year, $month){
                $category->with(['workers' => function($worker) use ($year, $month){
                    $worker->when($year && $month,function ($query) use ($year, $month){
                        $query->whereHas('events' , function($event) use($year, $month){
                            $event->where('type_id',1)
                                ->whereYear('date','<',$year)
                                ->orWhereYear('date','=',$year)
                                ->whereMonth('date','<=',$month);
                        })->with(['lastEvent' => function($lastEvent) use ($year, $month){
                            $lastEvent->whereYear('date','<',$year)
                                ->orWhereYear('date','=',$year)
                                ->whereMonth('date','<=',$month)
                                ->whereIn('type_id',[1,2]);
                        }]);
                    })->orderBy('id','DESC');
                }]);
            }])->get();
        foreach ($bases as $base){
            foreach ($base->categories as $category){
                $category->still_workers = $category->workers->filter(function ($value, $key) use ($month) {
                    if($value->lastEvent->type_id === 2){
                        return Carbon::parse($value->lastEvent->date)->format('m') === $month;
                    }
                    return true;
                });
            }
        }
        $workers = (new Worker($date))->fullPaket()->get();
        $workers = $workers->filter(function ($value, $key) use ($month) {
            if($value->lastEvent->type_id === 2){
                return Carbon::parse($value->lastEvent->date)->format('m') === $month;
            }
            return true;
        });
        $posts = Post::get();
        return response()->json([
            'success' => 'success',
            'bases' => $bases,
            'workers' => $workers,
            'date' => $date,
            'posts' => $posts
        ]);
    }

}
