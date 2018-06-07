<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'code'
    ];

    public function users()
    {
        return $this->hasMany('App\Users');
    }

    public function orders()
    {
        return $this->hasManyThrough('App\Order', 'App\User');
    }
}
