<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Schema::defaultStringLength(191);
        \URL::forceScheme('https');    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // デフォルトのエラーメッセージに加え、error_message.php からもメッセージを読み込む
        $errorMessages = trans('error_message');
    
        // confirmed ルールのカスタムメッセージを設定
        Validator::replacer('confirmed', function ($message, $attribute, $rule, $parameters) use ($errorMessages) {
            return $errorMessages[$rule] ?? $message;
        });

        // required ルールのカスタムメッセージを設定
        Validator::replacer('required', function ($message, $attribute, $rule, $parameters) use ($errorMessages) {
            return $errorMessages[$rule] ?? $message;
        });
    
        // max ルールのカスタムメッセージを設定
        Validator::replacer('max', function ($message, $attribute, $rule, $parameters) use ($errorMessages) {
            // attributeからフィールド名を取得
            $field = str_replace(['.', ' '], '_', $attribute); // 'content.title' などの場合を考慮
            return $errorMessages['custom'][$field]['max'] ?? $message;
        });
    }    
}
