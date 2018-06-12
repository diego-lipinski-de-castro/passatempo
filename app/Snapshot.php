<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    protected $fillable = [
        'from_date', 'to_date', 'data'
    ];

    protected $casts = [
        'data' => 'collection'
    ];
}
