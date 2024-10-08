<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forgot_password_users', function (Blueprint $table) {
            $table->integer('resend_count')->default(0); // resend_count カラムを追加
        });
    }

    public function down(): void
    {
        Schema::table('forgot_password_users', function (Blueprint $table) {
            $table->dropColumn('resend_count');
        });
    }
};
