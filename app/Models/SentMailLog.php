<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentMailLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'email',
        'additional'
    ];
    protected $casts = [
        'additional' => 'array'
    ];

}
