<x-field name="name" label="Naam" :value="$user?->name" required maxlength="100" />
<x-field name="email" label="E-mailadres" type="email" :value="$user?->email" required maxlength="255" />

<div>
    <label for="role" class="form-label">Rol</label>
    <select id="role" name="role" class="form-input">
        <option value="secretaris" @selected(old('role', $user?->role) === 'secretaris')>Secretaris (leden en kweeknummers)</option>
        <option value="beheerder" @selected(old('role', $user?->role) === 'beheerder')>Beheerder (ook lidsoorten, tarieven en gebruikers)</option>
    </select>
    @error('role') <p class="form-error">{{ $message }}</p> @enderror
</div>

<x-field name="password" label="Wachtwoord" type="password" :required="! $user" minlength="8" autocomplete="new-password"
         :hint="$user ? 'Laat leeg om het huidige wachtwoord te behouden.' : 'Minimaal 8 tekens.'" />
<x-field name="password_confirmation" label="Wachtwoord herhalen" type="password" :required="! $user" autocomplete="new-password" />
