<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\PaymentMethod;


/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wallet()        {
        
        return $this->hasOne(Wallet::class); 
    
    }
    public function transactions()  { 
        return $this->hasMany(WalletTransaction::class,'sender_id');
    
    }

    public function beneficiaries(){ return $this->hasMany(\App\Models\Beneficiary::class); }

   public function paymentMethods() { return $this->hasMany(PaymentMethod::class); }

   public function profile()
{
    return $this->hasOne(Profile::class);
}



}
