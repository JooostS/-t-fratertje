<?php

use App\Models\MemberType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Geldige invoer voor het lid-formulier; overschrijf losse velden per test.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function memberPayload(string $typeSlug = MemberType::ADULT, array $overrides = []): array
{
    $type = MemberType::where('slug', $typeSlug)->firstOrFail();

    return array_merge([
        'member_type_id' => $type->id,
        'first_name' => 'Anna',
        'last_name' => 'de Vries',
        'email' => 'anna@example.test',
        'birth_date' => '1985-04-12',
        'street' => 'Vinkenlaan',
        'house_number' => '12',
        'house_number_addition' => 'A',
        'postal_code' => '1234 AB',
        'city' => 'Utrecht',
        'breeding_number' => $type->is_nbvv_member ? 'NBVV-100200' : null,
        'issue_year' => $type->is_nbvv_member ? 2020 : null,
        'is_active' => 1,
    ], $overrides);
}
