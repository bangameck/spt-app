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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_number')->unique();
            $table->foreignId('leader_id')->constrained('leaders')->onDelete('cascade'); // Foreign key ke leaders
            $table->foreignId('field_coordinator_id')->constrained('field_coordinators')->onDelete('cascade'); // Foreign key ke field_coordinators
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('daily_deposit_amount', 10, 2); // 10 digit total, 2 di belakang koma
            $table->enum('status', ['active', 'expired', 'terminated', 'pending_renewal'])->default('active');
            $table->date('signed_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};
