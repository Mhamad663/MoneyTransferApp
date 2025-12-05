<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferService;

class TransferServiceSeeder extends Seeder
{
    public function run(): void
    {
        TransferService::insert([
            [
                'name' => 'Instant Wallet Transfer',
                'method' => 'wallet',
                'fee_percent' => 1.0,
                'fixed_fee' => 0.50,
                'speed' => 'instant',
                'active' => true,
            ],
            [
                'name' => 'Bank Deposit (Same Day)',
                'method' => 'bank',
                'fee_percent' => 1.5,
                'fixed_fee' => 1.00,
                'speed' => 'same_day',
                'active' => true,
            ],
            [
                'name' => 'Credit Card Payout',
                'method' => 'card',
                'fee_percent' => 2.0,
                'fixed_fee' => 1.00,
                'speed' => '3_days',
                'active' => true,
            ],
        ]);
    }
}
