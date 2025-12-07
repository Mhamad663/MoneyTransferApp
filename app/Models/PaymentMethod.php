<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperPaymentMethod
 */
class PaymentMethod extends Model {
  use HasFactory;

  protected $fillable = [
  'user_id','type',
  'brand','last4','exp_month','exp_year','token',
  'bank_name','iban','account_number',
  'is_default','status',
  'balance','currency',  
];

protected $casts = [
  'balance' => 'decimal:2',
  'is_default' => 'boolean',
];


  public function user(){ return $this->belongsTo(User::class); }
}
