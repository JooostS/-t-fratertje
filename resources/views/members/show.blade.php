<x-layouts.app :title="$member->full_name">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <h1 class="page-title">{{ $member->full_name }}</h1>
            <x-status-badge :member="$member" />
        </div>

        <div class="flex flex-wrap gap-2">
            @if ($member->trashed())
                <form method="POST" action="{{ route('members.restore', $member) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary">Terugzetten uit archief</button>
                </form>
            @else
                @if ($member->is_quarantine)
                    <form method="POST" action="{{ route('members.approve', $member) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-primary">Aanmelding goedkeuren</button>
                    </form>
                @endif
                <a href="{{ route('members.edit', $member) }}" class="btn-secondary">Wijzigen</a>
                <x-delete-form :action="route('members.destroy', $member)" :confirm="'Weet je zeker dat je '.$member->full_name.' wilt verwijderen? Het lid wordt gearchiveerd en kan worden teruggezet.'" />
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="card">
            <h2 class="mb-3 font-semibold text-brand-900">Lidgegevens</h2>
            <dl class="grid grid-cols-3 gap-y-2 text-sm">
                <dt class="text-stone-500">Lidsoort</dt><dd class="col-span-2">{{ $member->memberType->name }}</dd>
                <dt class="text-stone-500">E-mail</dt><dd class="col-span-2">{{ $member->email }}</dd>
                <dt class="text-stone-500">Geboortedatum</dt><dd class="col-span-2">{{ $member->birth_date->format('d-m-Y') }}</dd>
                <dt class="text-stone-500">Aangemeld op</dt><dd class="col-span-2">{{ $member->registered_at->format('d-m-Y') }}</dd>
                <dt class="text-stone-500">Lid per</dt><dd class="col-span-2">{{ $member->membership_starts_on?->format('d-m-Y') ?? '—' }}</dd>
                @if ($member->membership_ends_on)
                    <dt class="text-stone-500">Lidmaatschap eindigt</dt><dd class="col-span-2">{{ $member->membership_ends_on->format('d-m-Y') }}</dd>
                @endif
            </dl>
        </section>

        <section class="card">
            <h2 class="mb-3 font-semibold text-brand-900">Adres</h2>
            <address class="text-sm not-italic">
                {{ $member->address->full_street }}<br>
                {{ $member->address->postal_code }} {{ $member->address->city }}
            </address>

            <h2 class="mt-6 mb-3 font-semibold text-brand-900">NBvV</h2>
            <dl class="grid grid-cols-3 gap-y-2 text-sm">
                <dt class="text-stone-500">Kweeknummer</dt><dd class="col-span-2">{{ $member->nbvv_number ?? 'Geen (geen NBvV-lid)' }}</dd>
                @if ($member->breedingNumber)
                    <dt class="text-stone-500">Uitgiftejaar</dt><dd class="col-span-2">{{ $member->breedingNumber->issue_year }}</dd>
                @endif
            </dl>
        </section>
    </div>

    <section class="mt-6">
        <h2 class="mb-3 font-semibold text-brand-900">Facturen</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Datum</th><th>Soort</th><th>Jaar</th><th>Maanden</th><th class="text-right">Bedrag</th></tr></thead>
                <tbody>
                    @forelse ($member->invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->issued_on->format('d-m-Y') }}</td>
                            <td>{{ $invoice->type === 'refund' ? 'Restitutie' : 'Contributie' }}</td>
                            <td>{{ $invoice->year }}</td>
                            <td>{{ $invoice->months }}</td>
                            <td class="text-right">€ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-stone-500">Nog geen facturen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <p class="mt-6"><a href="{{ route('members.index') }}" class="text-sm text-brand-700 hover:underline">← Terug naar het overzicht</a></p>
</x-layouts.app>
