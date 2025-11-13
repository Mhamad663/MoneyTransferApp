<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('passport_path')->nullable();
            $table->string('id_card_path')->nullable();
            $table->enum('verification_status', ['Pending', 'Verified', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
