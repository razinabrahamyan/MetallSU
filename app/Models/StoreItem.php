<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    protected $fillable = ['storage_id','count','name','description','price'];
    use HasFactory;
    public function additions(){
        return $this->hasMany(Deduction::class,'item_id')->where('type','addition')->orderByDesc('created_at');
    }
    public function storage(){
        return $this->belongsTo(ItemStorage::class,'storage_id');
    }
}
