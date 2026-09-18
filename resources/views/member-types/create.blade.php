<x-layouts.app title="Lidsoort toevoegen">
    <h1 class="page-title">Lidsoort toevoegen</h1>

    <form method="POST" action="{{ route('member-types.store') }}" class="card mt-6 max-w-2xl space-y-4">
        @csrf
        <x-field name="name" label="Naam" required maxlength="50" />
        <x-field name="description" label="Omschrijving" maxlength="500" />
        <x-field name="amount" label="Contributie per jaar (€)" type="number" step="0.01" min="0" required hint="Geldt vanaf het lopende jaar; latere wijzigingen loop je via de tarieven." />

        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="is_nbvv_member" value="0">
            <input type="checkbox" name="is_nbvv_member" value="1" class="size-4 rounded border-stone-300" @checked(old('is_nbvv_member'))>
            Leden van deze soort zijn NBvV-lid (kweeknummer verplicht)
        </label>

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Opslaan</button>
            <a href="{{ route('member-types.index') }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
