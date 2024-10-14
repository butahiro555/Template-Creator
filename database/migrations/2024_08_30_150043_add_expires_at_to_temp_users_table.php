<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('temp_users', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable(); // 有効期限カラムを追加
        });
    }
    
    public function down()
    {
        Schema::table('temp_users', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
