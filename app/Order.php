<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'date', 'total'
    ];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item', 'orders_items')->withPivot('quantity');
    }

    public function total()
    {
        return $this->items->sum(function($item) {
            return $item->price * $item->pivot->quantity;
        });
    }
}
