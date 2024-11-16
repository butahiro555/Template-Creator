<?php

namespace Tests\Unit;

use App\Models\TempUser;
use App\Models\ForgotPasswordUser;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    protected $forgotPasswordUser;
    protected $tempUser;

    public function setUp(): void
    {
        parent::setUp();

        // 仮ユーザーを作成し、プロパティとして保持
        $this->tempUser = TempUser::factory()->create();

        // パスワードを忘れたユーザーを作成し、プロパティとして保持
        $this->forgotPasswordUser = ForgotPasswordUser::factory()->create();
    }

    // CleanExpiredForgotPasswordUsers.phpのテスト
    public function testCleanExpiredTempUsers(): void
    {
        // 有効期限を5分前に設定
        $this->tempUser->update(['expires_at' => now()->subMinutes(5)]);

        // Artisan::callでコマンドを実行
        $exitCode = Artisan::call('temp-users:clean-expired');

        // 終了コードが0（成功）であることを確認
        $this->assertEquals(0, $exitCode);

        // データベースから削除されたかどうかを検証
        $this->assertDatabaseMissing('temp_users', ['expires_at' => null]);
    }

    // CleanExpiredForgotPasswordUsers.phpのテスト
    public function testCleanExpiredForgotPasswordUsers(): void
    {
        // トークンの有効期限を5分前に設定
        $this->forgotPasswordUser->update(['expires_at' => now()->subMinutes(5)]);

        // Artisan::callでコマンドを実行
        $exitCode = Artisan::call('forgot-password-users:clean-expired');

        // 終了コードが0（成功）であることを確認
        $this->assertEquals(0, $exitCode);

        // データベースから削除されたかどうかを検証
        $this->assertDatabaseMissing('forgot_password_users', ['expires_at' => null]);
    }

    // スケジュール設定のテスト
    public function testScheduleConfiguration(): void
    {
        // スケジュールインスタンスを取得
        $schedule = app(Schedule::class);
    
        // スケジュールされたイベントを取得
        $events = collect($schedule->events());
    
        // temp-users:clean-expired がスケジュールされているか確認
        $this->assertTrue(
            $events->contains(function (Event $event) {
                return str_contains($event->getSummaryForDisplay(), 'temp-users:clean-expired')
                    && $event->expression === '* * * * *'; // everyMinute の cron 式
            }),
            'The command temp-users:clean-expired is not scheduled to run every minute.'
        );
    
        // forgot-password-users:clean-expired がスケジュールされているか確認
        $this->assertTrue(
            $events->contains(function (Event $event) {
                return str_contains($event->getSummaryForDisplay(), 'forgot-password-users:clean-expired')
                    && $event->expression === '* * * * *'; // everyMinute の cron 式
            }),
            'The command forgot-password-users:clean-expired is not scheduled to run every minute.'
        );
    }
}