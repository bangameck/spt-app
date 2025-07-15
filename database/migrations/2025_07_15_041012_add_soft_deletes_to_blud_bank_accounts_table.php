<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blud_bank_accounts', function (Blueprint $table) {
            $table->softDeletes(); // Perintah ini akan menambahkan kolom 'deleted_at'
        });
    }

    public function down(): void
    {
        Schema::table('blud_bank_accounts', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Menghapus kolom jika migrasi di-rollback
        });
    }
};
