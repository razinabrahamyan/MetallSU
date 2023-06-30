<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistics extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name','phone','car_type','car_number','date','query_id','cutter','loader','oxygen', 'status'
    ];
}
