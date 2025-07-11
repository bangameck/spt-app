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
        Schema::create('agreement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->onDelete('cascade');
            $table->enum('event_type', [
                'contract_extension',
                'location_added',
                'location_removed',
                'deposit_change',
                'termination',
                'renewal',
                'status_change',
                'other'
            ]);
            $table->json('old_value')->nullable(); // Simpan data lama dalam JSON
            $table->json('new_value')->nullable(); // Simpan data baru dalam JSON
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_histories');
    }
};
