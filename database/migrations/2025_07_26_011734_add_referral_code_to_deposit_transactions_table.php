<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposit_transactions', function (Blueprint $table) {
            $table->string('referral_code')->unique()->after('id')->comment('Kode referensi unik untuk transaksi');
        });
    }

    public function down(): void
    {
        Schema::table('deposit_transactions', function (Blueprint $table) {
            $table->dropColumn('referral_code');
        });
    }
};
