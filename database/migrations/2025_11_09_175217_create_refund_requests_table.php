<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('refund_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('transfer_id')->constrained()->cascadeOnDelete();
            $t->enum('kind', ['refund','dispute']);
            $t->string('reason')->nullable();            // e.g. “duplicate”, “wrong amount”, …
            $t->text('details')->nullable();             // user free text
            $t->json('evidence')->nullable();            // file names/links if you add upload later
            $t->enum('status', ['open','under_review','approved','rejected','refunded'])
              ->default('open');
            $t->text('resolution_note')->nullable();     // added by staff later
            $t->timestamps();

            $t->unique(['transfer_id']); // 1 request per transfer
        });
    }
    public function down(): void { Schema::dropIfExists('refund_requests'); }
};
