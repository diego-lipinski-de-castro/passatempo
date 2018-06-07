<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'price', 'type_id'
    ];

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function inOrders()
    {
        return $this->belongsToMany('App\Order', 'orders_items');
    }
}
