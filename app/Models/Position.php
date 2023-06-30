<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title','related_base_id', 'user_id', 'role'];

    public function items(){
        return $this->hasMany(Item::class);
    }

}
