<x-layouts.app title="Kweeknummer registreren">
    <h1 class="page-title">Kweeknummer registreren</h1>

    <form method="POST" action="{{ route('breeding-numbers.store') }}" class="card mt-6 max-w-2xl space-y-4">
        @csrf
        <div>
            <label for="member_id" class="form-label">Actief lid (zonder kweeknummer)</label>
            <select id="member_id" name="member_id" required class="form-input @error('member_id') border-red-500 @enderror">
                <option value="">Kies een lid…</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" @selected((int) old('member_id') === $member->id)>{{ $member->full_name }}</option>
                @endforeach
            </select>
            @error('member_id')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <x-field name="breeding_number" label="Kweeknummer (NBvV-lidnummer)" required maxlength="20" hint="Letters, cijfers en streepjes. Elk kweeknummer is uniek." />
        <x-field name="issue_year" label="Uitgiftejaar" type="number" :value="now()->year" min="1900" :max="now()->year" required />

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Opslaan</button>
            <a href="{{ route('breeding-numbers.index') }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
