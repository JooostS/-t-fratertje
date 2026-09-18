<x-layouts.app :title="$archived ? 'Archief leden' : 'Leden'">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="page-title">{{ $archived ? 'Archief: verwijderde leden' : 'Leden' }}</h1>
        <a href="{{ route('members.create') }}" class="btn-primary">Lid toevoegen</a>
    </div>

    <form method="GET" action="{{ route('members.index') }}" class="card mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
        <div class="lg:col-span-2">
            <label for="q" class="form-label">Zoeken</label>
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Naam of kweeknummer" class="form-input">
        </div>
        <div>
            <label for="member_type_id" class="form-label">Lidsoort</label>
            <select id="member_type_id" name="member_type_id" class="form-input">
                <option value="">Alle</option>
                @foreach ($memberTypes as $memberType)
                    <option value="{{ $memberType->id }}" @selected((int) request('member_type_id') === $memberType->id)>{{ $memberType->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-input">
                <option value="">Alle</option>
                <option value="active" @selected(request('status') === 'active')>Actief</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactief</option>
                <option value="quarantine" @selected(request('status') === 'quarantine')>In quarantaine</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('members.index', $archived ? ['archived' => 1] : []) }}" class="btn-secondary">Wissen</a>
        </div>

        <label class="flex items-center gap-2 text-sm sm:col-span-2 lg:col-span-5">
            <input type="checkbox" name="archived" value="1" @checked($archived) class="size-4 rounded border-stone-300"
                   onchange="this.form.submit()">
            Toon verwijderde (gearchiveerde) leden
        </label>
    </form>

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Lidsoort</th>
                    <th>Woonplaats</th>
                    <th>Kweeknummer</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr>
                        <td><a href="{{ route('members.show', $member) }}" class="font-medium text-brand-700 hover:underline">{{ $member->full_name }}</a></td>
                        <td>{{ $member->memberType->name }}</td>
                        <td>{{ $member->address->city }}</td>
                        <td>{{ $member->nbvv_number ?? '—' }}</td>
                        <td><x-status-badge :member="$member" /></td>
                        <td class="space-x-1 text-right whitespace-nowrap">
                            @if ($member->trashed())
                                <form method="POST" action="{{ route('members.restore', $member) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secondary btn-sm">Terugzetten</button>
                                </form>
                            @else
                                <a href="{{ route('members.edit', $member) }}" class="btn-secondary btn-sm">Wijzigen</a>
                                <x-delete-form :action="route('members.destroy', $member)" :confirm="'Weet je zeker dat je '.$member->full_name.' wilt verwijderen? Het lid wordt gearchiveerd en kan worden teruggezet.'" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-stone-500">Geen leden gevonden.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
</x-layouts.app>
