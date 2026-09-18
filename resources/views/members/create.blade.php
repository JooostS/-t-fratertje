<x-layouts.app title="Lid toevoegen">
    <h1 class="page-title">Lid toevoegen</h1>

    <form method="POST" action="{{ route('members.store') }}" class="card mt-6">
        @csrf
        <x-member-fields :member-types="$memberTypes" admin />

        <div class="mt-8 flex gap-2">
            <button type="submit" class="btn-primary">Opslaan</button>
            <a href="{{ route('members.index') }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
