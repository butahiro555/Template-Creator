<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class GuestUserSeeder extends Seeder
{
    public function run()
    {
        // ゲストユーザーを作成
        User::create([
            'name' => 'Guest User',
            'email' => 'guestuser@example.com',
            'password' => bcrypt('guestpassword'), // 仮パスワード
            'is_guest' => true, // ゲストフラグを追加
        ]);
    }
}