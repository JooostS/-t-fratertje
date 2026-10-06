<x-layouts.app title="Gebruikers">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="page-title">Gebruikers</h1>
        <a href="{{ route('users.create') }}" class="btn-primary">Gebruiker toevoegen</a>
    </div>

    @error('delete') <p class="form-error mt-4">{{ $message }}</p> @enderror

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead><tr><th>Naam</th><th>E-mailadres</th><th>Rol</th><th></th></tr></thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="font-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->isBeheerder() ? 'Beheerder' : 'Secretaris' }}</td>
                        <td class="space-x-1 text-right whitespace-nowrap">
                            <a href="{{ route('users.edit', $user) }}" class="btn-secondary btn-sm">Wijzigen</a>
                            @unless ($user->is(auth()->user()))
                                <x-delete-form :action="route('users.destroy', $user)" :confirm="'Weet je zeker dat je '.$user->name.' wilt verwijderen?'" />
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
