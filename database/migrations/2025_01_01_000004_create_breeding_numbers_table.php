<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeding_numbers', function (Blueprint $table) {
            $table->id();
            // unique() op member_id dwingt "0 of 1 kweeknummer per lid" af op databaseniveau.
            $table->foreignId('member_id')->unique()->constrained()->restrictOnDelete();
            // Het kweeknummer = het NBvV-lidnummer, uniek over alle leden (ook gearchiveerde).
            // Bestaat altijd uit precies 4 letters en/of cijfers, bv. "1TKY".
            $table->string('breeding_number', 4)->unique();
            $table->unsignedSmallInteger('issue_year');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeding_numbers');
    }
};
