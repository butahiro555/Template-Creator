<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResendCountToTempUsersTable extends Migration
{
    public function up()
    {
        Schema::table('temp_users', function (Blueprint $table) {
            $table->integer('resend_count')->default(0); // resend_count カラムを追加
        });
    }

    public function down()
    {
        Schema::table('temp_users', function (Blueprint $table) {
            $table->dropColumn('resend_count'); // カラムを削除
        });
    }
}
