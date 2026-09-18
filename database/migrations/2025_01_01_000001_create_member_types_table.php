<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            // Vaste sleutel waarmee de contributieregels een lidsoort herkennen (jeugdlid, volwassen, gastlid).
            $table->string('slug', 60)->unique();
            $table->text('description')->nullable();
            // Bepaalt of leden van dit type automatisch NBvV-lid zijn
            // en dus een kweeknummer (breeding_numbers) verplicht moeten hebben.
            $table->boolean('is_nbvv_member')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_types');
    }
};
