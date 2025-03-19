<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'group',
        'day',
        'pair_number',
        'subjects'
    ];

    protected $casts = [
        'subjects' => 'array',
    ];
}
