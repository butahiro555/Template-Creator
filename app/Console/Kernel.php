<?php

namespace App\Console;

use App\Console\Commands\CleanExpiredTempUsers; // コマンドクラスのインポート
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('temp-users:clean-expired')->everyMinute(); // 毎分実行
        $schedule->command('forgot-password-users:clean-expired')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
