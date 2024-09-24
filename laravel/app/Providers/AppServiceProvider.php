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
        \URL::forceScheme('http');    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // デフォルトのエラーメッセージに加え、error_message.php からもメッセージを読み込む
        $errorMessages = trans('error_message');

        Validator::replacer('confirmed', function ($message, $attribute, $rule, $parameters) use ($errorMessages) {
            return $errorMessages[$rule] ?? $message;
        });
    }
}
