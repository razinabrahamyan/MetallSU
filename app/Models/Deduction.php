<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'count',
        'item_id',
        'comment',
        'storage_id',
        'type',
        'date',
        'price'
    ];

    public function item(){
        return $this->belongsTo(StoreItem::class,'item_id');
    }

    public function storage(){
        return $this->belongsTo(ItemStorage::class,'storage_id');
    }

}
