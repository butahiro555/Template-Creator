<?php

namespace Database\Factories;

use App\Models\ForgotPasswordUser;
use App\Services\TokenService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ForgotPasswordUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'verification_code' => TokenService::generateVerificationCode(),
            'verification_code_expires_at' => TokenService::calculateExpiry(),
            'token' => TokenService::generateToken(),
            'token_expires_at' => TokenService::calculateExpiry(),
            'expires_at' => TokenService::calculateExpiry(60),
            'resend_count' => 0,
        ];
    }
}