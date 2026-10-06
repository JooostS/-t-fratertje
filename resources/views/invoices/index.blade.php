<x-layouts.app title="Facturen">
    <h1 class="page-title">Facturen</h1>
    <p class="mt-1 text-sm text-stone-600">Contributiefacturen worden automatisch aangemaakt bij het goedkeuren van een aanmelding; een afmelding levert een restitutie (negatief bedrag) op.</p>

    <section class="mt-6">
        <h2 class="mb-3 font-semibold text-brand-900">Jaaroverzicht</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Jaar</th><th class="text-right">Contributie</th><th class="text-right">Restitutie</th><th class="text-right">Netto</th></tr></thead>
                <tbody>
                    @forelse ($totalsByYear as $total)
                        <tr>
                            <td>{{ $total->year }}</td>
                            <td class="text-right">€ {{ number_format($total->contributie, 2, ',', '.') }}</td>
                            <td class="text-right">€ {{ number_format($total->restitutie, 2, ',', '.') }}</td>
                            <td class="text-right font-medium">€ {{ number_format($total->netto, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-stone-500">Nog geen facturen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <form method="GET" action="{{ route('invoices.index') }}" class="card mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
        <div class="lg:col-span-2">
            <label for="q" class="form-label">Zoeken</label>
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Naam of kweeknummer" class="form-input">
        </div>
        <div>
            <label for="type" class="form-label">Soort</label>
            <select id="type" name="type" class="form-input">
                <option value="">Alle</option>
                <option value="contribution" @selected(request('type') === 'contribution')>Contributie</option>
                <option value="refund" @selected(request('type') === 'refund')>Restitutie</option>
            </select>
        </div>
        <div>
            <label for="year" class="form-label">Jaar</label>
            <select id="year" name="year" class="form-input">
                <option value="">Alle</option>
                @foreach ($totalsByYear as $total)
                    <option value="{{ $total->year }}" @selected((int) request('year') === $total->year)>{{ $total->year }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('invoices.index') }}" class="btn-secondary">Wissen</a>
        </div>
    </form>

    <h2 class="mt-8 mb-3 font-semibold text-brand-900">Alle facturen</h2>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Datum</th><th>Lid</th><th>Soort</th><th>Jaar</th><th>Maanden</th><th class="text-right">Bedrag</th><th></th></tr></thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->issued_on->format('d-m-Y') }}</td>
                        <td><a href="{{ route('members.show', $invoice->member) }}" class="text-brand-700 hover:underline">{{ $invoice->member->full_name }}</a></td>
                        <td>{{ $invoice->type === 'refund' ? 'Restitutie' : 'Contributie' }}</td>
                        <td>{{ $invoice->year }}</td>
                        <td>{{ $invoice->months }}</td>
                        <td class="text-right">€ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                        <td class="text-right"><a href="{{ route('invoices.pdf', $invoice) }}" class="text-brand-700 hover:underline">PDF</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-stone-500">Nog geen facturen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $invoices->links() }}</div>
</x-layouts.app>
