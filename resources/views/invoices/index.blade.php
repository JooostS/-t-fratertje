<x-layouts.app title="Facturen">
    <h1 class="page-title">Facturen</h1>
    <p class="mt-1 text-sm text-stone-600">Contributiefacturen worden automatisch aangemaakt bij het goedkeuren van een aanmelding; een afmelding levert een restitutie (negatief bedrag) op.</p>

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead><tr><th>Datum</th><th>Lid</th><th>Soort</th><th>Jaar</th><th>Maanden</th><th class="text-right">Bedrag</th></tr></thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->issued_on->format('d-m-Y') }}</td>
                        <td><a href="{{ route('members.show', $invoice->member) }}" class="text-brand-700 hover:underline">{{ $invoice->member->full_name }}</a></td>
                        <td>{{ $invoice->type === 'refund' ? 'Restitutie' : 'Contributie' }}</td>
                        <td>{{ $invoice->year }}</td>
                        <td>{{ $invoice->months }}</td>
                        <td class="text-right">€ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-stone-500">Nog geen facturen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $invoices->links() }}</div>
</x-layouts.app>
