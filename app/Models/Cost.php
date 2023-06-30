<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['date', 'value', 'item_id' ,'count', 'comment', 'base_id', 'user_id', 'cashless', 'images'];

    protected $casts = [
        'date' => 'date',
        'images' => Json::class
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }


    public function responsibles(){
        return $this->belongsToMany(Responsible::class, 'costs_responsibles');
    }

}
