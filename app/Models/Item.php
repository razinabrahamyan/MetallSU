<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'position_id', 'parent_id', 'required_count', 'user_id', 'group_id', 'required_responsible'];


    public function subItems(){
        return $this->hasMany(Item::class,'parent_id');
    }
    public function parentItem(){
        return $this->belongsTo(Item::class,'parent_id');
    }
    public function position(){
        return $this->belongsTo(Position::class,'position_id');
    }
    public function costs(){
        return $this->hasMany(Cost::class,'item_id');
    }

    public function defaultReponsibleConnections(){
        return $this->hasMany(DefaultResponsible::class,'item_id');
    }
    public function defaultResponsibles(){
        return $this->belongsToMany(Responsible::class, 'default_responsibles')->whereNull('default_responsibles.deleted_at');
    }
}
