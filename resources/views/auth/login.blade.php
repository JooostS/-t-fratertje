<x-layouts.public title="Inloggen">
    <div class="mx-auto max-w-md">
        <h1 class="page-title">Inloggen</h1>
        <p class="mt-1 text-sm text-stone-600">Alleen voor de ledenadministratie.</p>

        <form method="POST" action="{{ route('login.store') }}" class="card mt-6 space-y-4">
            @csrf
            <x-field name="email" label="E-mailadres" type="email" required autofocus autocomplete="username" />
            <x-field name="password" label="Wachtwoord" type="password" required autocomplete="current-password" />

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1" class="size-4 rounded border-stone-300">
                Onthoud mij
            </label>

            <button type="submit" class="btn-primary w-full">Inloggen</button>
        </form>
    </div>
</x-layouts.public>
