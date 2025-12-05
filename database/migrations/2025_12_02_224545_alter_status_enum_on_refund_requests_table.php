<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->enum('status', [
                'open',
                'in_review',
                'resolved',
                'rejected',
                'flagged_fraud',
            ])->default('open')->change();
        });
    }

    public function down()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->enum('status', ['open'])  // or whatever you had before
                  ->default('open')
                  ->change();
        });
    }
};

