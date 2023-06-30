<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Responsible extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['type_id','name', 'user_id'];

    public function type(){
        return $this->belongsTo(ResponsibleType::class,'type_id');
    }

    public function costs(){
        return $this->belongsToMany(Cost::class, 'costs_responsibles');
    }
}
