<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPromotion
 */
class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
    'code','title','description','discount_percent',
    'start_date','end_date','active'
];

protected static function booted()
{
    static::creating(function ($promo) {
        if (!$promo->code) {
            $promo->code = 'PRM-' . strtoupper(Str::random(6));
        }
    });
}
}
