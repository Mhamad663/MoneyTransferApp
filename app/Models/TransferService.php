<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperTransferService
 */
class TransferService extends Model
{
    protected $fillable = ['name','method','fee_percent','fixed_fee','speed','active','code'];

    protected static function booted()
    {
        static::creating(function ($service) {
            if (!$service->code) {
                $service->code = 'SRV-' . strtoupper(Str::random(6));
            }
        });
    }

    public function calculateFee($amount)
    {
        return round(($amount * $this->fee_percent / 100) + $this->fixed_fee, 2);
    }
}
