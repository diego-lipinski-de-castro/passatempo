<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = [
        'from_date', 'to_date', 'data'
    ];

    protected $casts = [
        'data' => 'collection'
    ];
}
