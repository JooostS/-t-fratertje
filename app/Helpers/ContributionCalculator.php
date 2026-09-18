<?php

namespace App\Helpers;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Rekenregels voor contributie, puur en zonder database zodat ze eenvoudig te testen zijn.
 */
final class ContributionCalculator
{
    public const WAITING_WEEKS = 3;

    public const YOUTH_UNTIL_AGE = 18;

    /**
     * Ingangsdatum van een aan- of afmelding: na 3 weken wachttijd de eerstvolgende 1e van de maand.
     */
    public static function membershipStart(CarbonInterface $requestedOn): CarbonImmutable
    {
        $afterWaiting = $requestedOn->toImmutable()->startOfDay()->addWeeks(self::WAITING_WEEKS);

        return $afterWaiting->day === 1
            ? $afterWaiting
            : $afterWaiting->addMonthNoOverflow()->startOfMonth();
    }

    /**
     * Aantal maanden vanaf de gegeven 1e van de maand tot en met december.
     */
    public static function monthsLeftInYear(CarbonInterface $from): int
    {
        return 13 - $from->month;
    }

    /**
     * Een lid betaalt jeugdcontributie tot en met het kalenderjaar waarin hij of zij 18 wordt.
     */
    public static function isYouthTariff(CarbonInterface $birthDate, int $year): bool
    {
        return $year - $birthDate->year <= self::YOUTH_UNTIL_AGE;
    }

    public static function amountForMonths(float $annualAmount, int $months): float
    {
        return round($annualAmount * $months / 12, 2);
    }
}
