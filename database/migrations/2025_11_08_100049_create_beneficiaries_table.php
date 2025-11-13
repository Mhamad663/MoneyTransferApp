<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('beneficiaries', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained()->onDelete('cascade'); // owner
      $t->string('name');
      $t->string('country')->nullable();
      $t->string('payout_method'); // bank | cash | mobile_wallet | wallet
      // Bank details (nullable – used when payout_method = bank)
      $t->string('bank_name')->nullable();
      $t->string('account_number')->nullable();
      $t->string('iban')->nullable();
      $t->string('swift')->nullable();
      // Mobile wallet (nullable)
      $t->string('wallet_provider')->nullable(); // e.g., MTN, M-Pesa
      $t->string('wallet_phone')->nullable();
      // Platform wallet-to-wallet
      $t->string('platform_wallet_id')->nullable(); // WAL#########
      // Contact
      $t->string('email')->nullable();
      $t->string('phone')->nullable();
      $t->string('address')->nullable();

      $t->boolean('is_favorite')->default(false);
      $t->string('status')->default('active'); // active | disabled
      $t->text('notes')->nullable();

      $t->timestamps();

      // Helpful index
      $t->index(['user_id','name']);
    });
  }
  public function down(): void { Schema::dropIfExists('beneficiaries'); }
};
