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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('verification_code')->nullable();
            $table->string('token')->unique();
            $table->timestamp('token_expires_at')->nullable(); // トークンの有効期限カラムを追加
            $table->timestamp('verification_code_expires_at')->nullable(); // 認証コードの有効期限カラムを追加
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_users');
    }
};

