<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'shifts_begins_at' => $this->faker->time(),
            'shifts_ends_at' => $this->faker->time(),
        ];
    }
}
