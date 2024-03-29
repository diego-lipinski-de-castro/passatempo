<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $fillable = [
        'from_date', 'to_date', 'k', 'data'
    ];

    protected $casts = [
        'data' => 'collection'
    ];
}
