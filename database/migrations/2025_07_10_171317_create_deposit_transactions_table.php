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
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained()->onDelete('cascade');
            $table->date('deposit_date');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_validated')->default(false);
            $table->dateTime('validation_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Opsional: siapa yang mencatat
            $table->softDeletes();
            $table->timestamps();

            // Opsional: Pastikan hanya satu setoran per perjanjian per tanggal (jika logikanya begitu)
            $table->unique(['agreement_id', 'deposit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_transactions');
    }
};
