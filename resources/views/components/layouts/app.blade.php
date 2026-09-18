@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <header class="bg-brand-900 text-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-8 gap-y-2 px-4 py-3">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight">’t Fratertje</a>

            <nav class="flex flex-1 flex-wrap gap-1 text-sm">
                @foreach ([
                    ['dashboard', 'Dashboard', 'dashboard'],
                    ['members.index', 'Leden', 'leden*'],
                    ['member-types.index', 'Lidsoorten', 'lidsoorten*'],
                    ['breeding-numbers.index', 'Kweeknummers', 'kweeknummers*'],
                    ['invoices.index', 'Facturen', 'facturen*'],
                ] as [$route, $label, $pattern])
                    <a href="{{ route($route) }}"
                       @class(['rounded-md px-3 py-1.5 hover:bg-brand-700', 'bg-brand-700' => request()->is($pattern)])
                       @if (request()->is($pattern)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3 text-sm">
                @csrf
                <span class="text-brand-100">{{ auth()->user()->name }}</span>
                <button type="submit" class="btn-secondary btn-sm">Uitloggen</button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        <x-flash />

        {{ $slot }}
    </main>
</body>
</html>
