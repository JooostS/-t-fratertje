<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    public function definition(): array
    {
        $registeredAt = $this->faker->dateTimeBetween('-3 years', '-2 months');

        return [
            'member_type_id' => MemberType::factory(),
            'address_id' => Address::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_date' => $this->faker->dateTimeBetween('-80 years', '-19 years'),
            'is_active' => true,
            'is_quarantine' => false,
            'registered_at' => $registeredAt,
            'membership_starts_on' => (clone $registeredAt)->modify('first day of next month'),
        ];
    }

    public function youth(): static
    {
        return $this->state(fn () => [
            'birth_date' => $this->faker->dateTimeBetween('-17 years', '-6 years'),
        ]);
    }

    public function quarantine(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
            'is_quarantine' => true,
            'registered_at' => today(),
            'membership_starts_on' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
