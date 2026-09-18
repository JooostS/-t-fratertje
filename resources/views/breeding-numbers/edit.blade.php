<x-layouts.app title="Kweeknummer wijzigen">
    <h1 class="page-title">Kweeknummer wijzigen</h1>

    <form method="POST" action="{{ route('breeding-numbers.update', $breedingNumber) }}" class="card mt-6 max-w-2xl space-y-4">
        @csrf
        @method('PUT')
        <p class="text-sm text-stone-600">Lid: <strong>{{ $breedingNumber->member->full_name }}</strong></p>

        <x-field name="breeding_number" label="Kweeknummer (NBvV-lidnummer)" :value="$breedingNumber->breeding_number" required maxlength="20" />
        <x-field name="issue_year" label="Uitgiftejaar" type="number" :value="$breedingNumber->issue_year" min="1900" :max="now()->year" required />

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Wijzigingen opslaan</button>
            <a href="{{ route('breeding-numbers.index') }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
