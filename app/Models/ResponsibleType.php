<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponsibleType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'user_id'];

    public function responsibles(){
        return $this->hasMany(Responsible::class,'type_id');
    }
}
