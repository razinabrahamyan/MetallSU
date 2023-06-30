<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerEvent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['worker_id', 'date' ,'type_id', 'additional'];
    protected $casts = [
        'date' => 'date',
        'additional' => Json::class
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function type(){
        return $this->belongsTo(WorkerEventType::class);
    }
}
