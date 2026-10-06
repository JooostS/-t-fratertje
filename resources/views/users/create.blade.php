<x-layouts.app title="Gebruiker toevoegen">
    <h1 class="page-title">Gebruiker toevoegen</h1>

    <form method="POST" action="{{ route('users.store') }}" class="card mt-6 max-w-2xl space-y-4">
        @csrf
        @include('users.fields', ['user' => null])

        <div class="flex gap-2">
            <button type="submit" class="btn-primary">Opslaan</button>
            <a href="{{ route('users.index') }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
