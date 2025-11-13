<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'user_id','transfer_id','kind','reason','details','evidence','status','resolution_note'
    ];
    protected $casts = [
        'evidence' => 'array',
    ];

    public function transfer(){ return $this->belongsTo(\App\Models\Transfer::class); }
    public function user(){ return $this->belongsTo(\App\Models\User::class); }
}
