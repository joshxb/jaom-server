<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'phone' => $this->faker->unique()->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('joshua120629'),
            'status' => 'active',
            'nickname' => $this->generateNickname(),
            'image_blob' => null,
            'age' => $this->faker->numberBetween(18, 60),
            'location' => $this->faker->address(['country' => 'Philippines']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Define a custom state for the first user.
     */
    public function firstUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'firstname' => 'Joshua',
                'lastname' => 'Algadipe',
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    private function generateNickname(): string
    {
        $nickname = $this->faker->userName;
        $separator = '~!@#$%^&*()-=_+[]{}|;:,.<>?';
        $isTrue = $this->faker->boolean;

        return $nickname . $separator . ($isTrue ? 'true' : 'false');
    }
}
