<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('transfer_events', function (Blueprint $t) {
      $t->id();
      $t->foreignId('transfer_id')->constrained()->onDelete('cascade');
      $t->string('event');   // created | debited | credited | failed
      $t->text('meta')->nullable(); // json text
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('transfer_events'); }
};

