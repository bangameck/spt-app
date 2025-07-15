<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agreement_histories', function (Blueprint $table) {
            // Mengubah kolom event_type menjadi VARCHAR(255) agar bisa menampung teks yang lebih panjang
            $table->string('event_type', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agreement_histories', function (Blueprint $table) {
            // (Opsional) Mengembalikan ke tipe data lama jika migrasi di-rollback
            // Anda mungkin perlu menyesuaikan ini jika tipe data aslinya berbeda
            $table->string('event_type', 50)->change();
        });
    }
};
