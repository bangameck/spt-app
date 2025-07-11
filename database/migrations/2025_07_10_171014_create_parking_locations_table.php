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
        Schema::create('parking_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('road_section_id')->constrained()->onDelete('cascade'); // Foreign key ke road_sections
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            // Opsional: Untuk memastikan kombinasi road_section_id dan name unik
            $table->unique(['road_section_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_locations');
    }
};
