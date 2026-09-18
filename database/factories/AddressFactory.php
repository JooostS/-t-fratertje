<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName(),
            'house_number' => (string) $this->faker->numberBetween(1, 300),
            'house_number_addition' => $this->faker->optional(0.2)->randomElement(['A', 'B', 'bis']),
            'postal_code' => $this->faker->numerify('####').' '.strtoupper($this->faker->lexify('??')),
            'city' => $this->faker->city(),
        ];
    }
}
