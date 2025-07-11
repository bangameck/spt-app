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
        Schema::table('field_coordinators', function (Blueprint $table) {
            $table->string('phone_number')->after('address')->nullable(); // Menambahkan kolom phone_number setelah address
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_coordinators', function (Blueprint $table) {
            $table->dropColumn('phone_number'); // Menghapus kolom jika migrasi di-rollback
        });
    }
};
