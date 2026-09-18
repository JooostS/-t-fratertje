@props(['title', 'wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <header class="border-b border-stone-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-3">
            <a href="{{ route('home') }}" class="font-display text-2xl font-semibold tracking-tight text-brand-900">’t Fratertje</a>

            <nav class="flex flex-wrap items-center gap-1 text-sm" aria-label="Hoofdmenu">
                @foreach ([['info', 'Informatie'], ['contact.create', 'Contact'], ['cancellation.create', 'Afmelden']] as [$route, $label])
                    <a href="{{ route($route) }}"
                       @class(['rounded-md px-3 py-1.5 text-stone-700 hover:bg-brand-50', 'bg-brand-50 font-medium text-brand-900' => request()->routeIs($route)])
                       @if (request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach

                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-1.5 text-stone-700 hover:bg-brand-50">Administratie</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-md px-3 py-1.5 text-stone-700 hover:bg-brand-50">Inloggen</a>
                @endauth

                <a href="{{ route('signup.create') }}" class="btn-primary ml-1">Aanmelden</a>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        @if ($wide)
            @if (session('status') || $errors->any())
                <div class="mx-auto max-w-6xl px-4 pt-6"><x-flash /></div>
            @endif

            {{ $slot }}
        @else
            <div class="mx-auto max-w-3xl px-4 py-10">
                <x-flash />

                {{ $slot }}
            </div>
        @endif
    </main>

    <footer class="bg-brand-900 text-brand-100">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 sm:grid-cols-3">
            <div>
                <p class="font-display text-2xl font-semibold text-white">’t Fratertje</p>
                <p class="mt-2 max-w-xs text-sm leading-relaxed">Vogelvereniging voor iedereen die vogels houdt, kweekt of gewoon graag bekijkt.</p>
            </div>

            <nav aria-label="Voetmenu">
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('info') }}" class="hover:text-white hover:underline">Informatie</a></li>
                    <li><a href="{{ route('signup.create') }}" class="hover:text-white hover:underline">Aanmelden</a></li>
                    <li><a href="{{ route('cancellation.create') }}" class="hover:text-white hover:underline">Afmelden</a></li>
                    <li><a href="{{ route('contact.create') }}" class="hover:text-white hover:underline">Contact</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white hover:underline">Inloggen voor de administratie</a></li>
                </ul>
            </nav>

            <address class="space-y-2 text-sm not-italic">
                <p><a href="mailto:{{ config('club.email') }}" class="hover:text-white hover:underline">{{ config('club.email') }}</a></p>
                @if (config('club.phone'))
                    <p>{{ config('club.phone') }}</p>
                @endif
                @if (config('club.address'))
                    <p class="whitespace-pre-line">{{ config('club.address') }}</p>
                @endif
            </address>
        </div>

        <div class="border-t border-white/10">
            <details class="mx-auto max-w-6xl px-4 py-4 text-xs text-brand-100/80">
                <summary class="cursor-pointer hover:text-white">Fotoverantwoording</summary>
                <ul class="mt-3 grid gap-x-8 gap-y-1 sm:grid-cols-2">
                    @foreach (config('club.photos') as $photo)
                        <li>
                            {{ $photo['name'] }}:
                            <a href="{{ $photo['source'] }}" class="underline hover:text-white" rel="noopener">{{ $photo['artist'] }}</a>,
                            <a href="{{ $photo['license_url'] }}" class="underline hover:text-white" rel="noopener">{{ $photo['license'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </div>
    </footer>
</body>
</html>
