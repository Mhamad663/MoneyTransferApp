<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payment_methods', function (Blueprint $t) {
            $t->decimal('balance', 18, 2)->default(0)->after('is_default');
            $t->string('currency', 3)->default('USD')->after('balance');
        });
    }
    public function down(): void {
        Schema::table('payment_methods', function (Blueprint $t) {
            $t->dropColumn(['balance','currency']);
        });
    }
};
