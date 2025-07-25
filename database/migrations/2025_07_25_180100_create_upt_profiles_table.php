<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upt_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('UPT Perparkiran Dishub Pekanbaru');
            $table->string('app_name')->default('UPT Perparkiran Dishub Pekanbaru');
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upt_profiles');
    }
};
