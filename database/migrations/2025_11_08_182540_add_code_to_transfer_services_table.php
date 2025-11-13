<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('transfer_services', function (Blueprint $table) {
            $table->string('code', 20)->unique()->after('id')->nullable();
        });
    }

    public function down(): void {
        Schema::table('transfer_services', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};

