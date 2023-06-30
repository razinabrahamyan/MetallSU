<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    protected $fillable = ['worker_id', 'start_date', 'end_date', 'additional', 'paid', 'type', 'created_at'];
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'additional' => Json::class
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }
}
