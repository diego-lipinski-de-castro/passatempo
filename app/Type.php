<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'type_id'
    ];

    public function children()
    {
        return $this->hasMany('App\Type');
    }

    public function father()
    {
        return $this->belongsTo('App\Type');
    }

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
