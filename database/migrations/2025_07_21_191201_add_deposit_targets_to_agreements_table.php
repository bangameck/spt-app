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
        Schema::table('agreements', function (Blueprint $table) {
            // Menambahkan kolom setelah 'daily_deposit_amount'
            $table->decimal('monthly_deposit_target', 15, 2)->nullable()->after('daily_deposit_amount');
            $table->decimal('total_deposit_target', 15, 2)->nullable()->after('monthly_deposit_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropColumn(['monthly_deposit_target', 'total_deposit_target']);
        });
    }
};
