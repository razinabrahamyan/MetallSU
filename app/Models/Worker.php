<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['category_id', 'name', 'salary','official_salary', 'status_id', 'additional', 'user_id'];
    protected  $year;
    protected $month;

    public function __construct($date = null,$type = null ,array $attributes = [])
    {
        parent::__construct($attributes);
        $year = $date ? Carbon::parse($date)->format('Y') : null;
        $month = $date ? Carbon::parse($date)->format('m') : null;

        $this->year = $year;
        $this->month = $month;

    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function salaries(){
        return $this->hasMany(Salary::class);
    }

    public function events(){
        return $this->hasMany(WorkerEvent::class);
    }

    public function holidays(){
        return $this->hasMany(Holiday::class);
    }

    public function status(){
        return $this->belongsTo(WorkerStatus::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function lastEvent(){
        return $this->hasOne(WorkerEvent::class)->orderBy('date', 'DESC')->latest();
    }

    public function increases(){
        return $this->hasMany(SalaryIncrease::class);
    }
    public function lastIncrease($year = null,$month = null){
        return $this->hasOne(SalaryIncrease::class)->orderBy('date', 'DESC')->latest();
    }


    public function fullPaket(){
        return $this->with(['post','events' => function($event) {
            $event->with('type')
                ->when($this->year && $this->month,function ($query){
                $query->whereYear('date',$this->year)
                    ->whereMonth('date',$this->month);
            });
        },'salaries' => function($salaries) {
            $salaries->when($this->year && $this->month,function ($query){
                $query->whereYear('for',$this->year)
                    ->whereMonth('for',$this->month)
                    ->orderBy('for');
            });
        },'category' => function($category){
            $category->with('base');
        },'holidays' => function($holiday){
            $holiday->when($this->year && $this->month,function ($query){
                $query->whereYear('start_date',$this->year)
                    ->whereMonth('start_date',$this->month)
                    ->orWhereYear('end_date',$this->year)
                    ->whereMonth('end_date',$this->month);
            });
        },'lastIncrease'=>function($last_increase){
            $last_increase->when($this->year && $this->month,function ($query){
                $query->whereYear('date','<',$this->year)
                    ->orWhereYear('date','=',$this->year)
                    ->whereMonth('date','<=',$this->month);
            });
        },'increases'=>function($increase){
            $increase->when($this->year && $this->month,function ($query){
                $query->whereYear('date',$this->year)
                    ->whereMonth('date',$this->month);
            });

        },'lastEvent'=>function($lastEvent){
            $lastEvent->when($this->year && $this->month,function ($query){
                $query->whereYear('date','<',$this->year)
                    ->orWhereYear('date','=',$this->year)
                    ->whereMonth('date','<=',$this->month);
            })->whereIn('type_id',[1,2]);

        }])->when($this->year && $this->month,function ($query){
            $query->whereHas('events' , function($hiring){
                $hiring->where('type_id',1)
                    ->whereYear('date','<',$this->year)
                    ->orWhereYear('date','=',$this->year)
                    ->whereMonth('date','<=',$this->month);
            });
        });
    }

}
