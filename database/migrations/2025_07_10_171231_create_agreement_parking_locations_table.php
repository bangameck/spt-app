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
        Schema::create('agreement_parking_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_location_id')->constrained()->onDelete('cascade');
            $table->date('assigned_date'); // Kapan lokasi ini ditambahkan ke perjanjian
            $table->date('removed_date')->nullable(); // Kapan lokasi ini dihapus dari perjanjian
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status lokasi dalam perjanjian ini
            $table->timestamps();

            // Pastikan kombinasi agreement_id dan parking_location_id unik
            $table->unique(['agreement_id', 'parking_location_id'], 'agreement_loc_unique'); // Atau nama lain yang lebih pendek
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_parking_locations');
    }
};
