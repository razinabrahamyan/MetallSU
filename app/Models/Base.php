<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'user_id'];


    public function categories(){
        return $this->hasMany(Category::class);
    }
}
