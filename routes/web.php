<?php

use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\ThemesController;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', [TemplatesController::class, 'index'])
    ->name('templates.index');

// 認証済みユーザーのためのルートグループ
Route::middleware(['auth'])->group(function () {
    
    // 認証済みユーザー専用のルート（メール認証済みユーザーのみ）
    Route::middleware(['verified'])->group(function () {

        // テンプレート関連のルート
        Route::prefix('templates')->group(function () {
            Route::get('/', [TemplatesController::class, 'show'])
                ->name('templates.show');

            Route::post('store', [TemplatesController::class, 'store'])
                ->name('templates.store');

            Route::patch('update/{id}', [TemplatesController::class, 'update'])
                ->name('templates.update');

            Route::delete('destroy/{id}', [TemplatesController::class, 'destroy'])
                ->name('templates.destroy');

            // 検索機能
            Route::get('search', [TemplatesController::class, 'search'])
            ->name('templates.search');
        });
    });
});