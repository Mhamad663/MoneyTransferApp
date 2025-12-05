<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransferEvent
 */
class TransferEvent extends Model {
  protected $fillable = ['transfer_id','event','meta'];
  public function transfer(){ return $this->belongsTo(Transfer::class); }
}
