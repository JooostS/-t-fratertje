<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->restrictOnDelete();
            // 'contribution' = contributiefactuur, 'refund' = creditnota bij afmelding.
            $table->string('type', 20);
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('months');
            // Het jaartarief zoals dat op het moment van factureren gold (momentopname).
            $table->decimal('annual_amount', 8, 2);
            // Negatief bij een creditnota.
            $table->decimal('amount', 8, 2);
            $table->date('issued_on');
            $table->timestamps();

            $table->index(['member_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
