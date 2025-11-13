<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin IdeHelperTransfer
 */
class Transfer extends Model {
  protected $fillable = [
    'user_id','beneficiary_id','method','source','destination',
    'src_currency','dst_currency','amount_src','amount_dst','fee','fx_rate',
    'status','reference'
  ];
  public function user(){ return $this->belongsTo(User::class); }
  public function beneficiary(){ return $this->belongsTo(Beneficiary::class); }
  public function events(){ return $this->hasMany(TransferEvent::class); }

  public function service(){ return $this->belongsTo(\App\Models\TransferService::class,'service_id'); }

 public function refundRequest()
{
    return $this->hasOne(\App\Models\RefundRequest::class);
}

public function getHasRefundRequestAttribute(): bool
{
    // Avoid querying a table that doesn't exist
    if (! Schema::hasTable('refund_requests')) {
        return false;
    }
    // Avoid N+1: if relation is already loaded, use it
    if ($this->relationLoaded('refundRequest')) {
        return $this->refundRequest !== null;
    }
    return $this->refundRequest()->exists();
}

}
