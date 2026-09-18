<x-layouts.app :title="$memberType->name">
    <h1 class="page-title">{{ $memberType->name }}</h1>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('member-types.update', $memberType) }}" class="card space-y-4">
            @csrf
            @method('PUT')
            <h2 class="font-semibold text-brand-900">Gegevens</h2>
            <x-field name="name" label="Naam" :value="$memberType->name" required maxlength="50" />
            <x-field name="description" label="Omschrijving" :value="$memberType->description" maxlength="500" />

            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_nbvv_member" value="0">
                <input type="checkbox" name="is_nbvv_member" value="1" class="size-4 rounded border-stone-300"
                       @checked(old('is_nbvv_member', $memberType->is_nbvv_member))>
                Leden van deze soort zijn NBvV-lid (kweeknummer verplicht)
            </label>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Opslaan</button>
                <a href="{{ route('member-types.index') }}" class="btn-secondary">Terug</a>
            </div>
        </form>

        <section class="card">
            <h2 class="font-semibold text-brand-900">Contributietarieven</h2>
            <p class="mt-1 mb-4 text-sm text-stone-600">Een prijswijziging wordt vooraf aangekondigd en geldt alleen voor een komend jaar; het lopende jaar en de historie blijven ongewijzigd.</p>

            <table class="data-table mb-6">
                <thead><tr><th>Geldig vanaf</th><th class="text-right">Per jaar</th></tr></thead>
                <tbody>
                    @foreach ($rates as $rate)
                        <tr>
                            <td>{{ $rate->valid_from_year }} @if ($rate->valid_from_year > now()->year)<span class="badge bg-amber-100 text-amber-900">aangekondigd</span>@endif</td>
                            <td class="text-right">€ {{ number_format($rate->amount, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form method="POST" action="{{ route('contribution-rates.store', $memberType) }}" class="grid gap-4 sm:grid-cols-3 sm:items-end">
                @csrf
                <x-field name="valid_from_year" label="Ingangsjaar" type="number" :value="now()->year + 1" min="{{ now()->year + 1 }}" required />
                <x-field name="amount" label="Nieuw bedrag (€)" type="number" step="0.01" min="0" required />
                <button type="submit" class="btn-primary">Tarief vastleggen</button>
            </form>
        </section>
    </div>
</x-layouts.app>
