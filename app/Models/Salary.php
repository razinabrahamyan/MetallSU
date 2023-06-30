<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['amount', 'worker_id', 'for', 'bonus', 'additional'];
    protected $casts = [
        'additional' => Json::class
    ];
    public function worker(){
        return $this->belongsTo(Worker::class);
    }
}
