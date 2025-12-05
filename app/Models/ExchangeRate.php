<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'fixed_fee',
        'percent_fee',
        'is_active',
    ];

    protected $casts = [
        'rate'        => 'float',
        'fixed_fee'   => 'float',
        'percent_fee' => 'float',
        'is_active'   => 'boolean',
    ];
}
