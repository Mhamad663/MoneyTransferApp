<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score'); // 1..5
            $table->string('context')->default('service'); // e.g., 'service'
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'context']); // 1 rating per user per context
        });
    }
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
