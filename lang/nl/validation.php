<?php

return [
    'accepted' => 'Je moet dit vakje aanvinken om verder te gaan.',
    'after' => ':Attribute moet na :date liggen.',
    'before' => ':Attribute mag niet in de toekomst liggen.',
    'between' => [
        'numeric' => ':Attribute moet tussen :min en :max liggen.',
        'string' => ':Attribute moet tussen :min en :max tekens bevatten.',
    ],
    'boolean' => ':Attribute moet ja of nee zijn.',
    'date' => ':Attribute is geen geldige datum.',
    'email' => ':Attribute moet een geldig e-mailadres zijn.',
    'exists' => 'De gekozen waarde voor :attribute is ongeldig.',
    'integer' => ':Attribute moet een geheel getal zijn.',
    'max' => [
        'numeric' => ':Attribute mag niet groter zijn dan :max.',
        'string' => ':Attribute mag niet langer zijn dan :max tekens.',
    ],
    'min' => [
        'numeric' => ':Attribute moet minimaal :min zijn.',
        'string' => ':Attribute moet minimaal :min tekens bevatten.',
    ],
    'numeric' => ':Attribute moet een getal zijn.',
    'prohibited' => 'Een gastlid heeft geen :attribute.',
    'regex' => ':Attribute heeft een ongeldig formaat.',
    'required' => ':Attribute is verplicht.',
    'string' => ':Attribute moet tekst zijn.',
    'unique' => ':Attribute is al in gebruik.',

    'custom' => [
        'postal_code' => [
            'regex' => 'Vul een geldige postcode in, bijvoorbeeld 1234 AB.',
        ],
        'house_number' => [
            'regex' => 'Vul alleen cijfers in; een toevoeging als “A” of “bis” vul je hieronder in.',
        ],
        'breeding_number' => [
            'size' => 'Een kweeknummer bestaat uit precies 4 letters en/of cijfers.',
            'regex' => 'Een kweeknummer bestaat uit precies 4 letters en/of cijfers.',
            'unique' => 'Dit kweeknummer is al aan een ander lid toegekend.',
        ],
        'member_id' => [
            'unique' => 'Dit lid heeft al een (gearchiveerd) kweeknummer. Herstel of wijzig dat nummer.',
        ],
        'valid_from_year' => [
            'between' => 'Een prijswijziging geldt altijd voor een komend jaar (:min tot en met :max).',
        ],
    ],

    'attributes' => [
        'member_type_id' => 'lidsoort',
        'first_name' => 'voornaam',
        'last_name' => 'achternaam',
        'email' => 'e-mailadres',
        'password' => 'wachtwoord',
        'birth_date' => 'geboortedatum',
        'street' => 'straat',
        'house_number' => 'huisnummer',
        'house_number_addition' => 'toevoeging',
        'postal_code' => 'postcode',
        'city' => 'woonplaats',
        'breeding_number' => 'kweeknummer',
        'issue_year' => 'uitgiftejaar',
        'member_id' => 'lid',
        'name' => 'naam',
        'description' => 'omschrijving',
        'amount' => 'bedrag',
        'valid_from_year' => 'ingangsjaar',
        'agreement' => 'verklaring',
    ],
];
