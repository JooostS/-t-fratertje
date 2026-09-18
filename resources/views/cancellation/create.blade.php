<x-layouts.public title="Afmelden">
    <h1 class="page-title">Afmelden als lid</h1>
    <p class="mt-2 text-sm text-stone-600">
        Je lidmaatschap eindigt na 3 weken, per de eerstvolgende 1e van de maand. Contributie voor de resterende maanden van het jaar krijg je terug.
    </p>

    <form method="POST" action="{{ route('cancellation.store') }}" class="card mt-6 space-y-4">
        @csrf
        <x-field name="email" label="E-mailadres waarmee je bent aangemeld" type="email" required />
        <x-field name="birth_date" label="Geboortedatum" type="date" required />

        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="agreement" value="1" class="mt-0.5 size-4 rounded border-stone-300" @checked(old('agreement'))>
            <span>Ik bevestig dat ik mijn lidmaatschap van vogelvereniging ’t Fratertje wil opzeggen.</span>
        </label>
        @error('agreement')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-danger">Afmelden</button>
    </form>
</x-layouts.public>
