<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('payment_methods', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained()->onDelete('cascade');
      $t->string('type');                // card | bank
      // Card fields
      $t->string('brand')->nullable();   // Visa, MasterCard
      $t->string('last4',4)->nullable();
      $t->unsignedTinyInteger('exp_month')->nullable();
      $t->unsignedSmallInteger('exp_year')->nullable();
      $t->string('token')->nullable();   // gateway token (stub for now)
      // Bank fields
      $t->string('bank_name')->nullable();
      $t->string('iban')->nullable();
      $t->string('account_number')->nullable();
      // Common
      $t->boolean('is_default')->default(false);
      $t->string('status')->default('active'); // active | disabled
      $t->timestamps();
      $t->index(['user_id','type','is_default']);
    });
  }
  public function down(): void { Schema::dropIfExists('payment_methods'); }
};
