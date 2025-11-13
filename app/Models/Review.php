<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperReview
 */
class Review extends Model
{
    protected $fillable = ['user_id','score','comment','context'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
