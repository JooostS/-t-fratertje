<?php

namespace Database\Seeders;

use App\Models\MemberType;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * De drie vaste lidsoorten met hun startcontributie per jaar.
     */
    public function run(): void
    {
        $types = [
            [MemberType::factory()->jeugdlid(), 18.00],
            [MemberType::factory()->volwassenLid(), 36.00],
            [MemberType::factory()->gastlid(), 18.00],
        ];

        foreach ($types as [$factory, $amount]) {
            $factory->create()->contributionRates()->create([
                'valid_from_year' => 2024,
                'amount' => $amount,
            ]);
        }
    }
}
