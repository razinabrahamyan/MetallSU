<?php

namespace App\Traits;

use App\Models\Worker;

trait WorkerInfoTrait
{
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

    public function getWorkersColleagues($worker){
        return Worker::where('category_id',$worker->category_id)->with('lastIncrease')->where('id','!=',$worker->id)->orderBy('id','DESC')->get();
    }
}
