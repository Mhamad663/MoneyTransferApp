<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperWalletTransaction
 */
class WalletTransaction extends Model {
  use HasFactory;
  protected $fillable = ['sender_id','receiver_id','tx_type','amount','status','reference'];

  public function sender()   { return $this->belongsTo(User::class,'sender_id'); }
  public function receiver() { return $this->belongsTo(User::class,'receiver_id'); }
}
