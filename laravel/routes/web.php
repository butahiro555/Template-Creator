<?php

use App\Http\Controllers\TemplatesController;
use Illuminate\Support\Facades\Route;

// 未ログインユーザーのページ
Route::get('/', [TemplatesController::class, 'index']);

// 認証済みユーザーのためのルートグループ
Route::middleware(['auth'])->group(function () {
    
    // テンプレート関連のルート
    Route::prefix('templates')->group(function () {
        Route::get('/', [TemplatesController::class, 'show'])
            ->name('templates.show');

        Route::post('store', [TemplatesController::class, 'store'])
            ->name('templates.store');

        Route::put('update/{id}', [TemplatesController::class, 'update'])
            ->name('templates.update');

        Route::delete('destroy/{id}', [TemplatesController::class, 'destroy'])
            ->name('templates.destroy');
    });

    // 検索機能
    Route::get('search', [TemplatesController::class, 'search'])
        ->name('search');
});
