<x-layouts.app :title="$archived ? 'Archief kweeknummers' : 'Kweeknummers'">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="page-title">{{ $archived ? 'Archief: verwijderde kweeknummers' : 'Kweeknummers' }}</h1>
        <a href="{{ route('breeding-numbers.create') }}" class="btn-primary">Kweeknummer registreren</a>
    </div>

    <form method="GET" action="{{ route('breeding-numbers.index') }}" class="card mt-6 flex flex-wrap items-end gap-4">
        <div class="min-w-64 flex-1">
            <label for="q" class="form-label">Zoeken</label>
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Kweeknummer" class="form-input">
        </div>
        <label class="flex items-center gap-2 pb-2 text-sm">
            <input type="checkbox" name="archived" value="1" @checked($archived) class="size-4 rounded border-stone-300" onchange="this.form.submit()">
            Toon verwijderde kweeknummers
        </label>
        <button type="submit" class="btn-primary">Zoeken</button>
    </form>

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead><tr><th>Kweeknummer</th><th>Lid</th><th>Uitgiftejaar</th><th></th></tr></thead>
            <tbody>
                @forelse ($breedingNumbers as $breedingNumber)
                    <tr>
                        <td class="font-medium">{{ $breedingNumber->breeding_number }}</td>
                        <td>
                            <a href="{{ route('members.show', $breedingNumber->member) }}" class="text-brand-700 hover:underline">{{ $breedingNumber->member->full_name }}</a>
                            @if ($breedingNumber->member->trashed()) <span class="badge bg-stone-200 text-stone-700">Gearchiveerd</span> @endif
                        </td>
                        <td>{{ $breedingNumber->issue_year }}</td>
                        <td class="space-x-1 text-right whitespace-nowrap">
                            @if ($breedingNumber->trashed())
                                <form method="POST" action="{{ route('breeding-numbers.restore', $breedingNumber) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secondary btn-sm">Terugzetten</button>
                                </form>
                            @else
                                <a href="{{ route('breeding-numbers.edit', $breedingNumber) }}" class="btn-secondary btn-sm">Wijzigen</a>
                                <x-delete-form :action="route('breeding-numbers.destroy', $breedingNumber)" :confirm="'Weet je zeker dat je kweeknummer '.$breedingNumber->breeding_number.' wilt verwijderen? Het wordt gearchiveerd en blijft gereserveerd.'" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-stone-500">Geen kweeknummers gevonden.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $breedingNumbers->links() }}</div>
</x-layouts.app>
