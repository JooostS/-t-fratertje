<x-layouts.app title="Lid wijzigen">
    <h1 class="page-title">{{ $member->full_name }} wijzigen</h1>

    <form method="POST" action="{{ route('members.update', $member) }}" class="card mt-6">
        @csrf
        @method('PUT')
        <x-member-fields :member-types="$memberTypes" :member="$member" admin />

        <div class="mt-8 flex gap-2">
            <button type="submit" class="btn-primary">Wijzigingen opslaan</button>
            <a href="{{ route('members.show', $member) }}" class="btn-secondary">Annuleren</a>
        </div>
    </form>
</x-layouts.app>
