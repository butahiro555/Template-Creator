<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TempUser; // TempUser モデルのインポート
use Carbon\Carbon; // Carbon のインポート

class CleanExpiredTempUsers extends Command
{
    protected $signature = 'temp-users:clean-expired';
    protected $description = 'Remove expired temp users from the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // 現在の日時よりも前のレコードを削除
        $expired = TempUser::where(function($query) {
            $query->where('expires_at', '<', Carbon::now())
                  ->orWhereNull('expires_at'); // `expires_at` が `NULL` のレコードも削除
        })->delete();
    
        $this->info("Expired temp users deleted: $expired");
    }
}
