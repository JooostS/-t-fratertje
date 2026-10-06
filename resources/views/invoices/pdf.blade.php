@php
    $isRefund = $invoice->type === 'refund';
    $member = $invoice->member;
    $periodStart = \Carbon\CarbonImmutable::create($invoice->year, 13 - $invoice->months, 1)->locale('nl');
    $periodEnd = \Carbon\CarbonImmutable::create($invoice->year, 12, 31)->locale('nl');
    $money = fn ($amount) => '€ '.number_format($amount, 2, ',', '.');
@endphp
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #292524; margin: 0; }
        .band { background: #22563d; color: #fff; padding: 28px 48px; }
        .band h1 { margin: 0; font-size: 22px; letter-spacing: .5px; }
        .band p { margin: 4px 0 0; font-size: 11px; color: #d5e8dc; }
        .page { padding: 32px 48px; }
        table { width: 100%; border-collapse: collapse; }
        .meta td { vertical-align: top; padding: 0; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: .6px; color: #78716c; margin: 0 0 3px; }
        .value { margin: 0 0 12px; line-height: 1.5; }
        .lines { margin-top: 28px; }
        .lines th { background: #eef6f1; color: #14372a; font-size: 9px; text-transform: uppercase; letter-spacing: .5px; text-align: left; padding: 8px; }
        .lines td { padding: 10px 8px; border-bottom: 1px solid #d6d3d1; vertical-align: top; }
        .r { text-align: right; }
        .sub { color: #78716c; font-size: 10px; margin-top: 3px; }
        .total td { padding: 12px 8px; font-size: 13px; font-weight: bold; border-top: 2px solid #22563d; }
        .note { margin-top: 28px; padding: 12px 14px; background: #f5f5f4; line-height: 1.6; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; padding: 14px 48px; font-size: 9px; color: #78716c; border-top: 1px solid #d6d3d1; }
    </style>
</head>
<body>
    <div class="band">
        <h1>{{ $isRefund ? 'CREDITNOTA' : 'FACTUUR' }}</h1>
        <p>Ledenadministratie ’t Fratertje</p>
    </div>

    <div class="page">
        <table class="meta">
            <tr>
                <td style="width: 55%">
                    <p class="label">Factuur aan</p>
                    <p class="value">
                        <strong>{{ $member->full_name }}</strong><br>
                        {{ $member->address->full_street }}<br>
                        {{ $member->address->postal_code }} {{ $member->address->city }}
                    </p>
                </td>
                <td>
                    <p class="label">{{ $isRefund ? 'Creditnotanummer' : 'Factuurnummer' }}</p>
                    <p class="value"><strong>{{ $invoice->number }}</strong></p>
                    <p class="label">Datum</p>
                    <p class="value">{{ $invoice->issued_on->locale('nl')->isoFormat('D MMMM YYYY') }}</p>
                    @if ($member->nbvv_number)
                        <p class="label">Kweeknummer</p>
                        <p class="value">{{ $member->nbvv_number }}</p>
                    @endif
                </td>
            </tr>
        </table>

        <table class="lines">
            <thead>
                <tr><th>Omschrijving</th><th class="r">Jaartarief</th><th class="r">Maanden</th><th class="r">Bedrag</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $isRefund ? 'Restitutie contributie' : 'Contributie' }} {{ $invoice->year }} · {{ $member->memberType->name }}
                        <div class="sub">
                            @if ($isRefund)
                                Teruggave voor {{ $periodStart->isoFormat('D MMMM') }} t/m {{ $periodEnd->isoFormat('D MMMM YYYY') }}
                                na afmelding; het lidmaatschap eindigt {{ $member->membership_ends_on?->locale('nl')->isoFormat('D MMMM YYYY') }}.
                            @else
                                Lidmaatschap van {{ $periodStart->isoFormat('D MMMM') }} t/m {{ $periodEnd->isoFormat('D MMMM YYYY') }}
                                ({{ $invoice->months }} van 12 maanden).
                            @endif
                        </div>
                    </td>
                    <td class="r">{{ $money($invoice->annual_amount) }}</td>
                    <td class="r">{{ $invoice->months }}</td>
                    <td class="r">{{ $money($invoice->amount) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="3" class="r">{{ $isRefund ? 'Te ontvangen' : 'Totaal te betalen' }}</td>
                    <td class="r">{{ $money(abs($invoice->amount)) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="note">
            @if ($isRefund)
                Dit bedrag wordt aan u terugbetaald. Het is berekend over het aantal maanden na de einddatum van uw lidmaatschap.
            @else
                De contributie is berekend naar het aantal maanden dat u dit jaar lid bent: {{ $money($invoice->annual_amount) }} per jaar &times; {{ $invoice->months }}/12.
                @if (config('club.iban'))
                    <br>Gelieve over te maken op <strong>{{ config('club.iban') }}</strong> onder vermelding van <strong>{{ $invoice->number }}</strong>.
                @endif
            @endif
        </div>
    </div>

    <div class="footer">
        Ledenadministratie ’t Fratertje · {{ config('club.email') }}
        @if (config('club.phone')) · {{ config('club.phone') }} @endif
        @if (config('club.address')) · {{ config('club.address') }} @endif
    </div>
</body>
</html>
