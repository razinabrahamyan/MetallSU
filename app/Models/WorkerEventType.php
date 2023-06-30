<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerEventType extends Model
{
    protected $fillable = ['type'];
    use HasFactory;
}
