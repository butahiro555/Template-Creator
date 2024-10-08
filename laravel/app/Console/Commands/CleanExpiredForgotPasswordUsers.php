<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ForgotPasswordUser;
use Carbon\Carbon;

class CleanExpiredForgotPasswordUsers extends Command
{
    protected $signature = 'forgot-password-users:clean-expired';
    protected $description = 'Remove expired forgot password users from the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // 現在の日時よりも前のレコードを削除
        $expired = ForgotPasswordUser::where(function($query) {
            $query->where('expires_at', '<', Carbon::now())
                  ->orWhereNull('expires_at');
        })->delete();
    
        $this->info("Expired forgot password users deleted: {$expired}");
    }
}
