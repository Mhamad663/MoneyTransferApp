<?php

// app/Models/Agent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'name','address','city','country','phone','latitude','longitude','opening_hours','is_active'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_active'     => 'boolean',
        'latitude'      => 'float',
        'longitude'     => 'float',
    ];
}
