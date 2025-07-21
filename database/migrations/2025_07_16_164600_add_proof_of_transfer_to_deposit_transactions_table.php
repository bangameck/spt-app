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
        Schema::table('deposit_transactions', function (Blueprint $table) {
            // Menambahkan kolom untuk path gambar, bisa null (nullable) karena opsional,
            // diletakkan setelah kolom 'notes'.
            $table->string('proof_of_transfer')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_transactions', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('proof_of_transfer');
        });
    }
};
