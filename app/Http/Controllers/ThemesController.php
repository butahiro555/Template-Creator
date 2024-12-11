<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemesController extends Controller
{
    // ダークモード
    public function darkMode() {
        // 現在のダークモード状態を取得。デフォルトは「disabled」
        $currentMode = session('darkMode', 'disabled');
        
        // 現在の状態が「enabled」なら「disabled」に、そうでなければ「enabled」に切り替え
        $newMode = $currentMode === 'enabled' ? 'disabled' : 'enabled';
        
        // セッションに新しいモードを保存
        session(['darkMode' => $newMode]);
        
        // 新しいモードの状態をJSONレスポンスとして返す
        return response()->json(['darkMode' => $newMode]);
    }
}
