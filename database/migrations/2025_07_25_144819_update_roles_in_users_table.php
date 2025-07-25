<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom 'role' dengan daftar nilai yang baru
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'leader', 'field_coordinator', 'staff_keu', 'staff_pks') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke daftar nilai yang lama jika migrasi di-rollback
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'leader', 'field_coordinator', 'staff') NOT NULL");
    }
};
