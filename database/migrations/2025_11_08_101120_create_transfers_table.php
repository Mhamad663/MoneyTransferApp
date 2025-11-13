<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('transfers', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained()->onDelete('cascade');        // sender
      $t->foreignId('beneficiary_id')->nullable()->constrained()->nullOnDelete();
      $t->string('method');       // bank | card | wallet
      $t->string('source')->nullable(); // bank_account_id | card_token | wallet_id (sender)
      $t->string('destination')->nullable(); // IBAN/SWIFT | card_last4 | receiver_wallet_id
      $t->string('src_currency',3)->default('USD');
      $t->string('dst_currency',3)->default('USD');
      $t->decimal('amount_src',12,2);
      $t->decimal('amount_dst',12,2)->nullable();
      $t->decimal('fee',12,2)->default(0);
      $t->decimal('fx_rate',12,6)->default(1);
      $t->string('status')->default('pending'); // pending | processing | completed | failed
      $t->string('reference')->unique();
      $t->timestamps();
      $t->index(['user_id','status','method']);
    });
  }
  public function down(): void { Schema::dropIfExists('transfers'); }
};

