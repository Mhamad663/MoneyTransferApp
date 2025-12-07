<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'phone',
    'dob',
    'address',
    'company_name',
    'job_title',
    'salary',
    'nationality',
    'occupation',
    'marital_status',
    'education_level',
    'profile_photo_path',
    'passport_path',
    'id_card_path',
    'verification_status',
];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
