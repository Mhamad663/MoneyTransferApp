<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('wallet_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
      $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('set null');
      $table->string('tx_type');              // topup | transfer
      $table->decimal('amount', 12, 2);
      $table->string('status')->default('completed');
      $table->string('reference')->unique();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('wallet_transactions'); }
};

