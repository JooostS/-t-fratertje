<x-layouts.public title="Informatie" wide>
    <div class="h-48 overflow-hidden bg-stone-200 sm:h-64">
        <x-bird-photo photo="pimpelmees" eager class="h-full w-full object-cover object-[50%_35%]" />
    </div>

    <div class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="font-display text-4xl font-semibold tracking-tight text-brand-900 sm:text-5xl">Over ’t Fratertje</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-stone-700">
            Hier lees je hoe het lidmaatschap werkt: welke lidsoorten er zijn, wat het kost en wat er gebeurt als je je aan- of afmeldt.
        </p>

        <div class="mt-12 grid gap-12 lg:grid-cols-4">
            <nav class="lg:sticky lg:top-6 lg:self-start" aria-label="Op deze pagina">
                <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm lg:flex-col lg:gap-y-3">
                    <li><a href="#lidsoorten" class="text-brand-700 hover:underline">Lidsoorten</a></li>
                    <li><a href="#nbvv" class="text-brand-700 hover:underline">NBvV en kweeknummer</a></li>
                    <li><a href="#aanmelden" class="text-brand-700 hover:underline">Aanmelden</a></li>
                    <li><a href="#afmelden" class="text-brand-700 hover:underline">Afmelden</a></li>
                    <li><a href="#vragen" class="text-brand-700 hover:underline">Veelgestelde vragen</a></li>
                </ul>
            </nav>

            <div class="max-w-2xl space-y-14 leading-relaxed text-stone-700 lg:col-span-3">
                <section id="lidsoorten" class="scroll-mt-6">
                    <h2 class="font-display text-2xl font-semibold text-brand-900">Lidsoorten en contributie</h2>
                    <p class="mt-3">Er zijn drie soorten lidmaatschap. De contributie geldt per jaar (tarieven {{ now()->year }}).</p>

                    <dl class="mt-5">
                        @foreach ($memberTypes as $memberType)
                            <div class="flex items-baseline justify-between gap-6 border-b border-stone-200 py-4 first:border-t">
                                <div>
                                    <dt class="font-semibold text-brand-900">{{ $memberType->name }}</dt>
                                    <dd class="text-sm">{{ $memberType->description }}</dd>
                                </div>
                                <dd class="font-display text-xl font-semibold whitespace-nowrap text-brand-900">
                                    {{ $memberType->annualContribution() === null ? '—' : '€ '.number_format($memberType->annualContribution(), 2, ',', '.') }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>

                    <p class="mt-5">Een jeugdlid betaalt ook in het jaar waarin het 18 wordt nog de jeugdcontributie. Wie op 1 januari 18 wordt, heeft dus een heel jaar voordeel.</p>
                    <p class="mt-3">Prijswijzigingen worden altijd van tevoren aangekondigd en gelden voor het komende jaar. Het lopende jaar en eerdere facturen veranderen nooit.</p>
                </section>

                <section id="nbvv" class="scroll-mt-6">
                    <h2 class="font-display text-2xl font-semibold text-brand-900">NBvV en kweeknummer</h2>
                    <p class="mt-3">Jeugd- en volwassen leden zijn automatisch lid van de Nederlandse Bond van Vogelliefhebbers (NBvV). Je NBvV-lidnummer is je kweeknummer, en dat geef je op bij je aanmelding.</p>
                    <p class="mt-3">Een kweeknummer is uniek en hoort bij precies één lid. Een gastlid is geen NBvV-lid en heeft dus geen kweeknummer.</p>
                </section>

                <section id="aanmelden" class="scroll-mt-6">
                    <h2 class="font-display text-2xl font-semibold text-brand-900">Aanmelden</h2>
                    <p class="mt-3">Je meldt je aan met het <a href="{{ route('signup.create') }}" class="text-brand-700 underline underline-offset-2">aanmeldformulier</a>. Met het vinkje onderaan onderteken je digitaal. De administratie krijgt een melding en verwerkt je aanmelding.</p>
                    <p class="mt-3">Na je aanmelding duurt het nog drie weken voordat je lid bent. Je lidmaatschap gaat in op de eerstvolgende 1e van de maand na die drie weken, en je betaalt contributie over de resterende maanden van dat jaar.</p>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <caption class="pb-2 text-left text-stone-600">Twee voorbeelden</caption>
                            <thead class="border-b border-stone-300 text-brand-900">
                                <tr><th class="py-2 pr-4 font-semibold">Aangemeld op</th><th class="py-2 pr-4 font-semibold">Lid per</th><th class="py-2 font-semibold">Contributie over</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($examples as $example)
                                    <tr class="border-b border-stone-200">
                                        <td class="py-2 pr-4">{{ $example['requested']->translatedFormat('j F') }}</td>
                                        <td class="py-2 pr-4">{{ $example['start']->translatedFormat('j F') }}</td>
                                        <td class="py-2">{{ $example['months'] }} maanden</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section id="afmelden" class="scroll-mt-6">
                    <h2 class="font-display text-2xl font-semibold text-brand-900">Afmelden</h2>
                    <p class="mt-3">Opzeggen kan met het <a href="{{ route('cancellation.create') }}" class="text-brand-700 underline underline-offset-2">afmeldformulier</a>. Ook daarvoor geldt de wachttijd van drie weken: je lidmaatschap eindigt op de eerstvolgende 1e van de maand daarna.</p>
                    <p class="mt-3">Contributie die je al voor de resterende maanden van dat jaar hebt betaald, krijg je terug.</p>
                </section>

                <section id="vragen" class="scroll-mt-6">
                    <h2 class="font-display text-2xl font-semibold text-brand-900">Veelgestelde vragen</h2>

                    <div class="mt-4 divide-y divide-stone-200 border-y border-stone-200">
                        <details class="group py-4">
                            <summary class="cursor-pointer font-semibold text-brand-900">Kan ik lid worden zonder kweeknummer?</summary>
                            <p class="mt-2">Alleen als gastlid. Jeugd- en volwassen leden zijn lid van de NBvV en hebben altijd een kweeknummer.</p>
                        </details>
                        <details class="group py-4">
                            <summary class="cursor-pointer font-semibold text-brand-900">Welke lidsoort past bij mij?</summary>
                            <p class="mt-2">Ben je jonger dan 18, dan kies je jeugdlid; vanaf 18 jaar volwassen lid. Een gastlid kan elke leeftijd hebben.</p>
                        </details>
                        <details class="group py-4">
                            <summary class="cursor-pointer font-semibold text-brand-900">Wat gebeurt er met mijn gegevens als ik opzeg?</summary>
                            <p class="mt-2">Ze worden gearchiveerd in de ledenadministratie en niet definitief gewist. Alleen de administratie kan ze inzien.</p>
                        </details>
                        <details class="group py-4">
                            <summary class="cursor-pointer font-semibold text-brand-900">Ik heb een andere vraag.</summary>
                            <p class="mt-2">Stuur ons een bericht via de <a href="{{ route('contact.create') }}" class="text-brand-700 underline underline-offset-2">contactpagina</a>.</p>
                        </details>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.public>
