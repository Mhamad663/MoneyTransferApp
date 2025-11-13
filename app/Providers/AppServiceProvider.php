<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transfer;
use App\Observers\TransferObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-create wallet for newly registered users
        User::created(function ($user) {
            Wallet::create([
                'user_id'   => $user->id,
                'wallet_id' => 'WAL' . random_int(100000000, 999999999),
                'balance'   => 0,
            ]);
        });

        
    }
}
