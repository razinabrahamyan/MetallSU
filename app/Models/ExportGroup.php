<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title','user_id'];

    public function items(){
        return $this->hasMany(Item::class, 'group_id');
    }
}
