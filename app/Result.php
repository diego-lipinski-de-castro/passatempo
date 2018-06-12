<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'c1', 'c2', 'data'
    ];

    protected $casts = [
        'data' => 'collection'
    ];
}
