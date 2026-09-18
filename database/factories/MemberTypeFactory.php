<?php

namespace Database\Factories;

use App\Models\MemberType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'description' => $this->faker->sentence(),
            'is_nbvv_member' => true,
        ];
    }

    public function jeugdlid(): static
    {
        return $this->state(fn () => [
            'name' => 'Jeugdlid',
            'slug' => MemberType::YOUTH,
            'description' => 'Lid tot 18 jaar, automatisch NBvV-lid.',
            'is_nbvv_member' => true,
        ]);
    }

    public function volwassenLid(): static
    {
        return $this->state(fn () => [
            'name' => 'Volwassen lid',
            'slug' => MemberType::ADULT,
            'description' => 'Lid vanaf 18 jaar, automatisch NBvV-lid.',
            'is_nbvv_member' => true,
        ]);
    }

    public function gastlid(): static
    {
        return $this->state(fn () => [
            'name' => 'Gastlid',
            'slug' => MemberType::GUEST,
            'description' => 'Lid van alle leeftijden, geen NBvV-lid en dus geen kweeknummer.',
            'is_nbvv_member' => false,
        ]);
    }
}
