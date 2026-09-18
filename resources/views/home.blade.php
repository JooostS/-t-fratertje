<x-layouts.public title="Welkom" wide>
    {{-- Hero: de putter krijgt de rechterkant, de tekst staat links op de lichte achtergrond van de foto. --}}
    <section class="relative isolate overflow-hidden bg-white lg:min-h-[34rem]">
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:-z-10 lg:w-[68%]">
            <x-bird-photo photo="putter" eager
                class="h-64 w-full object-cover sm:h-80 lg:h-full lg:[mask-image:linear-gradient(to_right,transparent,#000_30%)]" />
        </div>

        <div class="mx-auto max-w-6xl px-4 py-10 lg:py-28">
            <div class="max-w-md">
                <h1 class="font-display text-4xl leading-[1.1] font-semibold tracking-tight text-brand-900 sm:text-5xl lg:text-6xl">
                    Vogels houden doe je beter samen.
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-stone-700">
                    ’t Fratertje is er voor iedereen die vogels houdt, kweekt of gewoon graag bekijkt. Sluit je aan bij de vereniging en bij de NBvV.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('signup.create') }}" class="btn-primary px-6 py-3 text-base">Aanmelden als lid</a>
                    <a href="{{ route('info') }}" class="btn-secondary px-6 py-3 text-base">Meer informatie</a>
                </div>
                <p class="mt-4 text-sm text-stone-600">Na je aanmelding ben je binnen drie weken lid.</p>
            </div>
        </div>
    </section>

    <section class="bg-canvas py-16">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 md:grid-cols-3">
            <div class="border-t-2 border-brand-900 pt-4">
                <h2 class="font-display text-xl font-semibold text-brand-900">Aangesloten bij de NBvV</h2>
                <p class="mt-2 leading-relaxed text-stone-700">Jeugd- en volwassen leden zijn automatisch lid van de Nederlandse Bond van Vogelliefhebbers en krijgen een eigen kweeknummer.</p>
            </div>
            <div class="border-t-2 border-brand-900 pt-4">
                <h2 class="font-display text-xl font-semibold text-brand-900">Alles online geregeld</h2>
                <p class="mt-2 leading-relaxed text-stone-700">Aanmelden en opzeggen doe je met een formulier op deze site. De administratie verwerkt het en houdt je gegevens bij.</p>
            </div>
            <div class="border-t-2 border-brand-900 pt-4">
                <h2 class="font-display text-xl font-semibold text-brand-900">Voor jong en oud</h2>
                <p class="mt-2 leading-relaxed text-stone-700">Jeugdleden betalen een lager tarief, ook in het jaar waarin ze 18 worden. Gasten zijn welkom in elke leeftijd.</p>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="font-display text-3xl font-semibold tracking-tight text-brand-900">Vogels om van te houden</h2>

            <div class="mt-8 grid gap-4 md:h-[36rem] md:grid-cols-3 md:grid-rows-2">
                @foreach ([
                    ['kanarie', 'md:row-span-2', 'object-[50%_15%] md:object-center'],
                    ['zebravink', 'md:col-span-2', 'object-center'],
                    ['pimpelmees', 'md:col-span-2', 'object-center'],
                ] as [$photo, $placement, $position])
                    <figure class="relative aspect-[4/3] overflow-hidden bg-stone-200 md:aspect-auto {{ $placement }}">
                        <x-bird-photo :photo="$photo" class="h-full w-full object-cover {{ $position }}" />
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 pt-12 text-white">
                            <span class="font-display text-xl font-semibold">{{ config("club.photos.{$photo}.name") }}</span>
                            <span class="block text-sm text-white/90">{{ config("club.photos.{$photo}.caption") }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-brand-50 py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="font-display text-3xl font-semibold tracking-tight text-brand-900">Lid worden in drie stappen</h2>

            <ol class="mt-10 grid gap-10 md:grid-cols-3">
                <li>
                    <span class="font-display text-5xl font-semibold text-accent" aria-hidden="true">1</span>
                    <h3 class="mt-2 text-lg font-semibold text-brand-900">Vul het formulier in</h3>
                    <p class="mt-2 leading-relaxed text-stone-700">Je gegevens, je lidsoort en, voor jeugd- en volwassen leden, je kweeknummer. Met een vinkje onderteken je digitaal.</p>
                </li>
                <li>
                    <span class="font-display text-5xl font-semibold text-accent" aria-hidden="true">2</span>
                    <h3 class="mt-2 text-lg font-semibold text-brand-900">Wij verwerken je aanmelding</h3>
                    <p class="mt-2 leading-relaxed text-stone-700">De administratie krijgt een seintje en controleert je gegevens. Vanaf je aanmelding geldt een wachttijd van drie weken.</p>
                </li>
                <li>
                    <span class="font-display text-5xl font-semibold text-accent" aria-hidden="true">3</span>
                    <h3 class="mt-2 text-lg font-semibold text-brand-900">Lid per de eerste van de maand</h3>
                    <p class="mt-2 leading-relaxed text-stone-700">Je lidmaatschap gaat in op de eerstvolgende 1e na die drie weken. Je betaalt contributie over de resterende maanden van het jaar.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 md:grid-cols-5">
            <div class="md:col-span-2">
                <h2 class="font-display text-3xl font-semibold tracking-tight text-brand-900">Contributie {{ now()->year }}</h2>
                <p class="mt-3 max-w-sm leading-relaxed text-stone-700">Per jaar. Meld je halverwege het jaar aan, dan betaal je naar rato. Prijswijzigingen worden vooraf aangekondigd en gelden voor het komende jaar.</p>
            </div>

            <dl class="md:col-span-3">
                @foreach ($memberTypes as $memberType)
                    <div class="flex items-baseline justify-between gap-6 border-b border-stone-200 py-4 first:border-t">
                        <div>
                            <dt class="font-semibold text-brand-900">{{ $memberType->name }}</dt>
                            <dd class="text-sm text-stone-600">{{ $memberType->description }}</dd>
                        </div>
                        <dd class="font-display text-2xl font-semibold whitespace-nowrap text-brand-900">
                            {{ $memberType->annualContribution() === null ? '—' : '€ '.number_format($memberType->annualContribution(), 2, ',', '.') }}
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="relative isolate overflow-hidden bg-brand-900 py-20 text-white">
        <x-bird-photo photo="parkiet" class="absolute inset-0 -z-10 h-full w-full object-cover opacity-25" />
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="font-display text-4xl font-semibold tracking-tight sm:text-5xl">Kom erbij.</h2>
            <p class="mt-3 max-w-md text-lg text-brand-100">Aanmelden duurt een paar minuten. Heb je eerst een vraag? Stuur ons een bericht.</p>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                <a href="{{ route('signup.create') }}" class="btn-secondary px-6 py-3 text-base">Aanmelden als lid</a>
                <a href="{{ route('contact.create') }}" class="font-medium underline underline-offset-4 hover:text-white">Neem contact op</a>
            </div>
        </div>
    </section>
</x-layouts.public>
