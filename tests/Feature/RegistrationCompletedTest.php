<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\RegistrationCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationCompleted(): void
    {
        $mailable = new RegistrationCompleted;

        $this->assertEquals(
            '[Template Creator]登録完了のお知らせ',
            $mailable->envelope()->subject
        );

        $content = $mailable->content();

        $this->assertEquals('emails.registration-completed', $content->view);
    }
}