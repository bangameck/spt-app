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
        Schema::create('blud_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('account_number')->unique(); // Nomor rekening harus unik
            $table->string('account_name');
            $table->boolean('is_active')->default(true); // Untuk menandai rekening mana yang sedang aktif
            $table->date('start_date'); // Tanggal mulai berlaku rekening ini
            $table->date('end_date')->nullable(); // Tanggal tidak berlaku lagi (jika diganti)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blud_bank_accounts');
    }
};
