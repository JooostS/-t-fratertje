<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreedingNumberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            // Een echt kweeknummer is meestal 1 cijfer gevolgd door 3 letters, bv. "1TKY".
            'breeding_number' => $this->faker->unique()->regexify('[0-9][A-Z]{3}'),
            'issue_year' => $this->faker->numberBetween(2015, now()->year),
        ];
    }
}
