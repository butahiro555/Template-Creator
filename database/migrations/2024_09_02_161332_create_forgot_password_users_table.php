<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forgot_password_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('verification_code')->nullable();
            $table->string('token')->unique();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forgot_password_users');
    }
};
