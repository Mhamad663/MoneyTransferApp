<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('address');
            $table->string('job_title')->nullable()->after('company_name');
            $table->decimal('salary', 12, 2)->nullable()->after('job_title');
            $table->string('nationality')->nullable()->after('salary');
            $table->string('occupation')->nullable()->after('nationality');
            $table->string('marital_status')->nullable()->after('occupation');
            $table->string('education_level')->nullable()->after('marital_status');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'job_title',
                'salary',
                'nationality',
                'occupation',
                'marital_status',
                'education_level',
            ]);
        });
    }
};
