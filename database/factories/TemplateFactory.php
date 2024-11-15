<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    public function definition(): array
    {
        // Userを作成して、そのIDをuser_idに設定
        $user = User::factory()->create();

        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'user_id' => $user->id,  // user_id に直接 user の id を設定
        ];
    }
}