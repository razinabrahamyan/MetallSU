<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStorage extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','title'];

    public function items(){
        return $this->hasMany(StoreItem::class,'storage_id');
    }


    public function recentItemsLimited(){
        return $this->items()->limit(10)->orderByDesc('created_at');
    }
    public function availableItems(){
        return $this->hasMany(StoreItem::class,'storage_id')->where('count','>',0);
    }

    public function deductions(){
        return $this->hasMany(Deduction::class,'storage_id')->where('type','deduction')->orWhereNull('type')->limit(30)->orderByDesc('created_at');
    }


    public function history(){
        return $this->hasMany(Deduction::class,'storage_id')->orderByDesc('created_at');
    }
}
