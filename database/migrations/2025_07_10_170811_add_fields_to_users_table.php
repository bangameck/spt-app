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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name'); // Tambah username, unik
            $table->enum('role', ['admin', 'leader', 'field_coordinator', 'staff'])->default('field_coordinator')->after('password'); // Tambah role
            $table->string('img')->nullable()->after('role'); // Tambah path foto profil
            $table->softDeletes()->after('updated_at'); // Tambah soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
