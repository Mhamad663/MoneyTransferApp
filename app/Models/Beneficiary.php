<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperBeneficiary
 */
class Beneficiary extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id','name','country','payout_method','bank_name','account_number','iban','swift',
    'wallet_provider','wallet_phone','platform_wallet_id','email','phone','address',
    'is_favorite','status','notes',
  ];

  public function user(){ return $this->belongsTo(User::class); }
}
