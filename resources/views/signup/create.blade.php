<x-layouts.public title="Aanmelden">
    <h1 class="page-title">Aanmelden als lid</h1>
    <p class="mt-2 text-sm text-stone-600">
        Je aanmelding wordt eerst door de administratie verwerkt. Na 3 weken ben je lid, per de eerstvolgende 1e van de maand;
        de contributie wordt berekend over de resterende maanden van dat jaar.
    </p>

    <form method="POST" action="{{ route('signup.store') }}" class="card mt-6">
        @csrf
        <x-member-fields :member-types="$memberTypes" />

        <label class="mt-8 flex items-start gap-2 text-sm">
            <input type="checkbox" name="agreement" value="1" class="mt-0.5 size-4 rounded border-stone-300" @checked(old('agreement'))>
            <span>Ik verklaar dat bovenstaande gegevens juist zijn en ga akkoord met het lidmaatschap van vogelvereniging ’t Fratertje (digitale handtekening).</span>
        </label>
        @error('agreement')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <div class="mt-6">
            <button type="submit" class="btn-primary">Aanmelden</button>
        </div>
    </form>
</x-layouts.public>
