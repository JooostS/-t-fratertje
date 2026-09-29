<x-layouts.app title="Geschiedenis kweeknummers">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="page-title">Geschiedenis kweeknummers</h1>
        <a href="{{ route('breeding-numbers.index') }}" class="btn-secondary">Terug naar kweeknummers</a>
    </div>
    <p class="mt-1 text-sm text-stone-600">Alle ooit uitgegeven kweeknummers, actief en gearchiveerd, op uitgiftejaar.</p>

    <div class="table-wrap mt-6">
        <table class="data-table">
            <thead><tr><th>Kweeknummer</th><th>Uitgiftejaar</th><th>Lid</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($breedingNumbers as $breedingNumber)
                    <tr>
                        <td class="font-medium">{{ $breedingNumber->breeding_number }}</td>
                        <td>{{ $breedingNumber->issue_year }}</td>
                        <td><a href="{{ route('members.show', $breedingNumber->member) }}" class="text-brand-700 hover:underline">{{ $breedingNumber->member->full_name }}</a></td>
                        <td>
                            @if ($breedingNumber->trashed())
                                <span class="badge bg-stone-200 text-stone-700">Gearchiveerd {{ $breedingNumber->deleted_at->format('d-m-Y') }}</span>
                            @else
                                <span class="badge bg-brand-100 text-brand-900">Actief</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-stone-500">Nog geen kweeknummers uitgegeven.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $breedingNumbers->links() }}</div>
</x-layouts.app>
