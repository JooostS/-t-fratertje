<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { padding: 8px; border-bottom: 1px solid #ccc; text-align: left; }
        .r { text-align: right; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>{{ $invoice->type === 'refund' ? 'Creditnota' : 'Factuur' }} {{ $invoice->number }}</h1>
    <p class="muted">Ledenadministratie 't Fratertje<br>Datum: {{ $invoice->issued_on->format('d-m-Y') }}</p>

    <p>
        {{ $invoice->member->full_name }}<br>
        {{ $invoice->member->address->full_street }}<br>
        {{ $invoice->member->address->postal_code }} {{ $invoice->member->address->city }}
    </p>

    <table>
        <thead><tr><th>Omschrijving</th><th class="r">Jaartarief</th><th class="r">Maanden</th><th class="r">Bedrag</th></tr></thead>
        <tbody>
            <tr>
                <td>{{ $invoice->type === 'refund' ? 'Restitutie contributie' : 'Contributie' }} {{ $invoice->year }}</td>
                <td class="r">€ {{ number_format($invoice->annual_amount, 2, ',', '.') }}</td>
                <td class="r">{{ $invoice->months }}</td>
                <td class="r">€ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="r"><strong>Totaal: € {{ number_format($invoice->amount, 2, ',', '.') }}</strong></p>
</body>
</html>
