<?php

namespace Database\Factories;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dataset>
 */
class DatasetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => fake()->unique()->numerify('###'),
            'email'=> fake()->unique()->safeEmail(),
            'full_name'=> fake()->unique()->name(),
            'country_code' => strtoupper(fake()->lexify(str_repeat('?', 2))),

        ];
    }
}
