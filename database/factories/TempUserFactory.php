<?php

namespace Database\Factories;

use App\Models\TempUser;
use App\Services\TokenService;
use Illuminate\Database\Eloquent\Factories\Factory;

class TempUserFactory extends Factory
{
    protected $model = TempUser::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'verification_code' => TokenService::generateVerificationCode(),
            'token' => TokenService::generateToken(),
        ];
    }
}
