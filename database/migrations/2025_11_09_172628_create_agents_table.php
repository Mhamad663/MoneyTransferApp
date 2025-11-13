<?php

// database/migrations/xxxx_xx_xx_create_agents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('address')->nullable();
            $t->string('city')->nullable();
            $t->string('country')->nullable();
            $t->string('phone')->nullable();
            $t->decimal('latitude', 10, 7);   // 6–7 decimal places for ~1cm precision
            $t->decimal('longitude', 10, 7);
            $t->json('opening_hours')->nullable(); // e.g. {"mon-fri":"9:00–18:00","sat":"10:00–14:00"}
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
