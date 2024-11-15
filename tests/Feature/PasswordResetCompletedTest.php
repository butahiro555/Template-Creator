<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PasswordResetCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function testPasswordResetCompleted(): void
    {
        $mailable = new PasswordResetCompleted;

        $this->assertEquals(
            '[Template Creator]パスワードのリセット完了のお知らせ',
            $mailable->envelope()->subject
        );

        //
        $content = $mailable->content();

        $this->assertEquals('emails.password-reset-completed', $content->view);
    }
}