<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerStatus extends Model
{
    protected $fillable = ['status'];
    use HasFactory;
}
