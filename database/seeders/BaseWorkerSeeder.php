<?php

namespace Database\Seeders;

use App\Models\Base;
use App\Models\Category;
use App\Models\SalaryIncrease;
use App\Models\Worker;
use App\Models\WorkerEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaseWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $base = new Base();
        $base->title = 'Черный';
        $base->user_id = 1;
        $base->save();
        $base = new Base();
        $base->title = 'Цветной';
        $base->user_id = 1;
        $base->save();
        $base = new Base();
        $base->title = 'АПАРИНКИ';
        $base->user_id = 1;
        $base->save();
        $base = new Base();
        $base->title = 'Бухгалтерия';
        $base->user_id = 1;
        $base->save();
        $base = new Base();
        $base->title = 'Ж/Д ДОМОДЕДОВО';
        $base->user_id = 1;
        $base->save();
        $base = new Base();
        $base->title = 'Офис';
        $base->user_id = 1;
        $base->save();


        for($i = 1; $i < 7 ; $i ++){
            for($j = 1; $j < 5 ; $j ++){
                $category = new Category();
                $category->base_id = $i;
                $category->title = 'category_'.$i.'_'.$j;
                $category->save();
            }

        }

        for($i = 0; $i < 4 ; $i ++){
            for($j = 1; $j < 7 ; $j ++){
                $workers_number = rand(4,20);
                for($k = 1; $k <= $workers_number ; $k ++){
                    $cat_id = ($i * 6) +  $j;
                    $salary = rand(50,200) * 1000;
                    $worker = new Worker();
                    $worker->user_id = 1;
                    $worker->name = 'worker_'.$cat_id.Str::random(5);
                    $worker->category_id =$cat_id;
                    $worker->status_id = rand(1,3);
                    $worker->salary = $salary;
                    $worker->official_salary = rand(10,40) * 1000;
                    $worker->additional = Str::random(rand(0,40));
                    $worker->save();

                    $event = new WorkerEvent();
                    $event->worker_id = $worker->id;
                    $event->date = Carbon::parse('01.01.2022')->format('Y-m-d');
                    $event->type_id = 1;
                    $event->additional = ['type' => 'hiring'];
                    $event->save();

                    $increase = new SalaryIncrease();
                    $increase->worker_id = $worker->id;
                    $increase->date = Carbon::parse('01.01.2022')->format('Y-m-d');
                    $increase->new_value = $salary;
                    $increase->initial = true;
                    $increase->save();
                }

            }
        }
    }
}
