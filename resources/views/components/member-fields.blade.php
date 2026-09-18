@props(['memberTypes', 'member' => null, 'admin' => false])

<div class="space-y-8">
    <fieldset>
        <legend class="mb-3 text-base font-semibold text-brand-900">Persoonsgegevens</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="first_name" label="Voornaam" :value="$member?->first_name" required maxlength="100" />
            <x-field name="last_name" label="Achternaam" :value="$member?->last_name" required maxlength="100" />
            <x-field name="email" label="E-mailadres" type="email" :value="$member?->email" required />
            <x-field name="birth_date" label="Geboortedatum" type="date" :value="$member?->birth_date?->format('Y-m-d')" required />

            <div class="sm:col-span-2">
                <label for="member_type_id" class="form-label">Lidsoort</label>
                <select id="member_type_id" name="member_type_id" required
                        class="form-input @error('member_type_id') border-red-500 @enderror">
                    <option value="">Kies een lidsoort…</option>
                    @foreach ($memberTypes as $memberType)
                        <option value="{{ $memberType->id }}" data-nbvv="{{ $memberType->is_nbvv_member ? 1 : 0 }}"
                                @selected((int) old('member_type_id', $member?->member_type_id) === $memberType->id)>
                            {{ $memberType->name }}
                        </option>
                    @endforeach
                </select>
                @error('member_type_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend class="mb-3 text-base font-semibold text-brand-900">Adres</legend>
        <div class="grid gap-4 sm:grid-cols-6">
            <x-field name="street" label="Straat" :value="$member?->address?->street" required maxlength="100" class="sm:col-span-3" />
            <x-field name="house_number" label="Huisnummer" :value="$member?->address?->house_number" required inputmode="numeric" maxlength="5" class="sm:col-span-2" />
            <x-field name="house_number_addition" label="Toevoeging" :value="$member?->address?->house_number_addition" maxlength="10" class="sm:col-span-1" />
            <x-field name="postal_code" label="Postcode" :value="$member?->address?->postal_code" required placeholder="1234 AB" maxlength="7" class="sm:col-span-2" />
            <x-field name="city" label="Woonplaats" :value="$member?->address?->city" required maxlength="100" class="sm:col-span-4" />
        </div>
    </fieldset>

    <fieldset id="breeding-fields">
        <legend class="mb-1 text-base font-semibold text-brand-900">NBvV-gegevens</legend>
        <p class="mb-3 text-sm text-stone-600">Jeugd- en volwassen leden zijn automatisch lid van de NBvV en hebben een uniek kweeknummer. Gastleden hebben geen kweeknummer.</p>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="breeding_number" label="Kweeknummer (NBvV-lidnummer)" :value="$member?->breedingNumber?->breeding_number" maxlength="20" hint="Letters, cijfers en streepjes, bijvoorbeeld NBVV-123456." />
            <x-field name="issue_year" label="Uitgiftejaar" type="number" :value="$member?->breedingNumber?->issue_year ?? now()->year" min="1900" :max="now()->year" />
        </div>
    </fieldset>

    @if ($admin && ! $member?->is_quarantine)
        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="size-4 rounded border-stone-300"
                   @checked(old('is_active', $member?->is_active ?? true))>
            Actief lid
        </label>
    @endif
</div>

<script>
    (() => {
        const select = document.getElementById('member_type_id');
        const fields = document.getElementById('breeding-fields');
        const sync = () => {
            const needsBreedingNumber = select.selectedOptions[0]?.dataset.nbvv === '1';
            fields.hidden = ! needsBreedingNumber;
            fields.querySelectorAll('input').forEach((input) => input.disabled = ! needsBreedingNumber);
        };
        select.addEventListener('change', sync);
        sync();
    })();
</script>
