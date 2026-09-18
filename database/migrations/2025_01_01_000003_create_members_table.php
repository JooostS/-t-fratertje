<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_type_id')->constrained()->restrictOnDelete();
            // Elk lid heeft precies één adres (1-op-1).
            $table->foreignId('address_id')->unique()->constrained()->restrictOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email');
            $table->date('birth_date');
            $table->boolean('is_active')->default(false);
            // Nieuwe aanmeldingen staan in quarantaine tot de administratie ze verwerkt.
            $table->boolean('is_quarantine')->default(true);
            $table->date('registered_at');
            $table->date('membership_starts_on')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->date('membership_ends_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
