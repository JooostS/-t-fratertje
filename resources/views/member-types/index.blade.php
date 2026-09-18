<x-layouts.app title="Lidsoorten">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="page-title">Lidsoorten</h1>
        <a href="{{ route('member-types.create') }}" class="btn-primary">Lidsoort toevoegen</a>
    </div>

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead><tr><th>Naam</th><th>Omschrijving</th><th>NBvV-lid</th><th>Leden</th><th></th></tr></thead>
            <tbody>
                @foreach ($memberTypes as $memberType)
                    <tr>
                        <td class="font-medium">{{ $memberType->name }}</td>
                        <td class="text-stone-600">{{ $memberType->description }}</td>
                        <td>{{ $memberType->is_nbvv_member ? 'Ja' : 'Nee' }}</td>
                        <td>{{ $memberType->members_count }}</td>
                        <td class="space-x-1 text-right whitespace-nowrap">
                            <a href="{{ route('member-types.edit', $memberType) }}" class="btn-secondary btn-sm">Wijzigen &amp; tarieven</a>
                            <x-delete-form :action="route('member-types.destroy', $memberType)" :confirm="'Weet je zeker dat je de lidsoort '.$memberType->name.' wilt verwijderen?'" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
