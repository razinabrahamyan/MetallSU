<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryIncrease extends Model
{
    protected $fillable = ['worker_id','old_value', 'new_value', 'date', 'initial'];
    use HasFactory;
    use SoftDeletes;
    public function worker(){
        return $this->belongsTo(Worker::class);
    }
    protected $casts = [
        'date' => 'date',
    ];
}
