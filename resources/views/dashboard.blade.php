<x-layouts.app title="Dashboard">
    <h1 class="page-title">Dashboard</h1>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="card">
            <p class="text-sm text-stone-600">Actieve leden</p>
            <p class="mt-1 text-3xl font-semibold text-brand-900">{{ $activeCount }}</p>
        </div>
        <div class="card">
            <p class="text-sm text-stone-600">Aanmeldingen in quarantaine</p>
            <p class="mt-1 text-3xl font-semibold text-accent">{{ $quarantineMembers->count() }}</p>
        </div>
        <a href="{{ route('members.index', ['archived' => 1]) }}" class="card block transition hover:border-brand-600">
            <p class="text-sm text-stone-600">Gearchiveerde leden</p>
            <p class="mt-1 text-3xl font-semibold text-stone-700">{{ $archivedCount }}</p>
        </a>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2">
            <h2 class="mb-3 text-lg font-semibold text-brand-900">Te verwerken aanmeldingen</h2>

            @if ($quarantineMembers->isEmpty())
                <p class="card text-sm text-stone-600">Er zijn geen aanmeldingen die op verwerking wachten.</p>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Naam</th><th>Lidsoort</th><th>Aangemeld</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($quarantineMembers as $member)
                                <tr>
                                    <td><a href="{{ route('members.show', $member) }}" class="font-medium text-brand-700 hover:underline">{{ $member->full_name }}</a></td>
                                    <td>{{ $member->memberType->name }}</td>
                                    <td>{{ $member->registered_at->format('d-m-Y') }}</td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('members.approve', $member) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-primary btn-sm">Goedkeuren</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section>
            <h2 class="mb-3 text-lg font-semibold text-brand-900">Actieve leden per lidsoort</h2>
            <ul class="card space-y-3 text-sm">
                @foreach ($memberTypes as $memberType)
                    <li class="flex items-center justify-between">
                        <span>{{ $memberType->name }}</span>
                        <span class="badge bg-brand-100 text-brand-900">{{ $memberType->members_count }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('members.create') }}" class="btn-primary">Lid toevoegen</a>
                <a href="{{ route('members.index') }}" class="btn-secondary">Alle leden</a>
            </div>
        </section>
    </div>
</x-layouts.app>
