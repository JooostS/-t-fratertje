<?php

use App\Helpers\ContributionCalculator;
use Carbon\CarbonImmutable;

it('starts membership on the first of the month after the 3 week waiting period', function (string $requestedOn, string $expectedStart, int $expectedMonths) {
    $start = ContributionCalculator::membershipStart(CarbonImmutable::parse($requestedOn));

    expect($start->toDateString())->toBe($expectedStart)
        ->and(ContributionCalculator::monthsLeftInYear($start))->toBe($expectedMonths);
})->with([
    'opgave 5 maart (voorbeeld 1)' => ['2026-03-05', '2026-04-01', 9],
    'opgave 25 maart (voorbeeld 2)' => ['2026-03-25', '2026-05-01', 8],
    'wachttijd eindigt precies op de 1e' => ['2026-03-11', '2026-04-01', 9],
    'opgave op 10 december gaat over naar volgend jaar' => ['2026-12-10', '2027-01-01', 12],
    'opgave op 20 december' => ['2026-12-20', '2027-02-01', 11],
    'opgave op 1 januari' => ['2026-01-01', '2026-02-01', 11],
]);

it('charges youth tariff up to and including the year a member turns 18', function (string $birthDate, int $year, bool $expectedYouth) {
    expect(ContributionCalculator::isYouthTariff(CarbonImmutable::parse($birthDate), $year))->toBe($expectedYouth);
})->with([
    '17 jaar' => ['2009-06-15', 2026, true],
    'wordt 18 in dit jaar' => ['2008-12-31', 2026, true],
    'wordt 18 op 1 januari, nog een jaar voordeel' => ['2008-01-01', 2026, true],
    'werd vorig jaar 18' => ['2007-05-05', 2026, false],
]);

it('calculates the amount pro rata per month', function () {
    expect(ContributionCalculator::amountForMonths(36.00, 9))->toBe(27.00)
        ->and(ContributionCalculator::amountForMonths(18.00, 8))->toBe(12.00)
        ->and(ContributionCalculator::amountForMonths(36.00, 12))->toBe(36.00)
        ->and(ContributionCalculator::amountForMonths(18.00, 7))->toBe(10.50);
});
