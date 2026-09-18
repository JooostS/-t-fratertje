<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contactgegevens van de vereniging
    |--------------------------------------------------------------------------
    |
    | Pas deze aan in .env. Telefoon en adres worden alleen getoond als ze zijn ingevuld.
    |
    */

    'email' => env('CLUB_EMAIL', 'secretaris@fratertje.test'),
    'phone' => env('CLUB_PHONE'),
    'address' => env('CLUB_ADDRESS'),

    /*
    |--------------------------------------------------------------------------
    | Foto's op de publieke pagina's (Wikimedia Commons)
    |--------------------------------------------------------------------------
    |
    | Bestanden staan in public/images/birds. Maker, licentie en bron worden onderaan elke
    | pagina vermeld, zoals de Creative Commons-licenties vragen.
    |
    */

    'photos' => [
        'putter' => [
            'file' => 'putter.jpg',
            'width' => 1280,
            'height' => 853,
            'name' => 'Putter',
            'alt' => 'Een putter met rood gezicht en gele vleugelstreep zit op een distel.',
            'caption' => 'Herkenbaar aan het rode gezicht en de gele vleugelstreep.',
            'artist' => 'MinoZig',
            'license' => 'CC BY-SA 4.0',
            'license_url' => 'https://creativecommons.org/licenses/by-sa/4.0',
            'source' => 'https://commons.wikimedia.org/wiki/File:European_goldfinch_(Carduelis_carduelis),_Israel_01.jpg',
        ],
        'kanarie' => [
            'file' => 'kanarie.jpg',
            'width' => 960,
            'height' => 1239,
            'name' => 'Kanarie',
            'alt' => 'Een gele kanarie zit op een groene zitstok en kijkt in de camera.',
            'caption' => 'Al eeuwen een geliefde kooivogel, gekweekt in vele kleuren.',
            'artist' => 'Mounir Neddi',
            'license' => 'CC BY-SA 4.0',
            'license_url' => 'https://creativecommons.org/licenses/by-sa/4.0',
            'source' => 'https://commons.wikimedia.org/wiki/File:Domestic_canary_from_Morocco.jpg',
        ],
        'zebravink' => [
            'file' => 'zebravink.jpg',
            'width' => 960,
            'height' => 728,
            'name' => 'Zebravink',
            'alt' => 'Een zebravink met oranje wang en rode snavel zit op een tak.',
            'caption' => 'Kleine prachtvink uit Australië, goed te houden en te kweken.',
            'artist' => 'Peripitus',
            'license' => 'CC BY-SA 3.0',
            'license_url' => 'https://creativecommons.org/licenses/by-sa/3.0',
            'source' => 'https://commons.wikimedia.org/wiki/File:Taeniopygia_guttata_-_profile_-_dundee_wildlife_park.jpg',
        ],
        'pimpelmees' => [
            'file' => 'pimpelmees.jpg',
            'width' => 960,
            'height' => 640,
            'name' => 'Pimpelmees',
            'alt' => 'Een pimpelmees met blauwe kap en gele borst zit op een stengel.',
            'caption' => 'Een vaste gast in de Nederlandse tuin.',
            'artist' => 'Alexis Lours',
            'license' => 'CC BY 4.0',
            'license_url' => 'https://creativecommons.org/licenses/by/4.0',
            'source' => 'https://commons.wikimedia.org/wiki/File:Eurasian_Blue_Tit_2025_04_07.jpg',
        ],
        'parkiet' => [
            'file' => 'parkiet.jpg',
            'width' => 960,
            'height' => 640,
            'name' => 'Parkieten',
            'alt' => '',
            'caption' => 'Een zwerm wilde parkieten in Australië.',
            'artist' => 'JJ Harrison',
            'license' => 'CC BY-SA 3.0',
            'license_url' => 'https://creativecommons.org/licenses/by-sa/3.0',
            'source' => 'https://commons.wikimedia.org/wiki/File:Budgerigar-_Mount_Hope.jpg',
        ],
    ],

];
