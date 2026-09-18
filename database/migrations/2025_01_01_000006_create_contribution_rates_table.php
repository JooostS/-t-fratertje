<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tariefhistorie: een prijswijziging is een nieuwe rij met een later ingangsjaar,
        // waardoor lopend jaar en historie nooit veranderen.
        Schema::create('contribution_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_type_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('valid_from_year');
            $table->decimal('amount', 8, 2);
            $table->timestamps();

            $table->unique(['member_type_id', 'valid_from_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_rates');
    }
};
