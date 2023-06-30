<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['base_id','title'];

    public function workers(){
        return $this->hasMany(Worker::class);
    }

    public function base(){
        return $this->belongsTo(Base::class);
    }
}
