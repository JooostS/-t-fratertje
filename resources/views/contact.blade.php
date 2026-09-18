<x-layouts.public title="Contact" wide>
    <div class="mx-auto grid max-w-6xl gap-12 px-4 py-12 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <h1 class="font-display text-4xl font-semibold tracking-tight text-brand-900">Contact</h1>
            <p class="mt-4 leading-relaxed text-stone-700">Een vraag over het lidmaatschap, je kweeknummer of je aanmelding? Stuur ons een bericht, dan reageert de secretaris zo snel mogelijk.</p>

            <address class="mt-8 space-y-3 not-italic">
                <p>
                    <span class="block text-sm text-stone-500">E-mail</span>
                    <a href="mailto:{{ config('club.email') }}" class="font-medium text-brand-700 underline underline-offset-2">{{ config('club.email') }}</a>
                </p>
                @if (config('club.phone'))
                    <p>
                        <span class="block text-sm text-stone-500">Telefoon</span>
                        {{ config('club.phone') }}
                    </p>
                @endif
                @if (config('club.address'))
                    <p>
                        <span class="block text-sm text-stone-500">Adres</span>
                        <span class="whitespace-pre-line">{{ config('club.address') }}</span>
                    </p>
                @endif
            </address>

            <x-bird-photo photo="zebravink" class="mt-10 hidden aspect-[4/3] w-full object-cover lg:block" />
        </div>

        <form method="POST" action="{{ route('contact.store') }}" class="card space-y-4 lg:col-span-3 lg:self-start">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <x-field name="name" label="Naam" required maxlength="100" autocomplete="name" />
                <x-field name="email" label="E-mailadres" type="email" required autocomplete="email" />
            </div>

            <div>
                <label for="message" class="form-label">Bericht</label>
                <textarea id="message" name="message" rows="6" required minlength="10" maxlength="2000"
                          @error('message') aria-invalid="true" @enderror
                          class="form-input @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Bericht versturen</button>
        </form>
    </div>
</x-layouts.public>
