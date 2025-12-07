<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'country',
        'phone',
        'latitude',
        'longitude',
        'opening_hours',
        'is_active',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_active'     => 'boolean',
        'latitude'      => 'float',
        'longitude'     => 'float',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
