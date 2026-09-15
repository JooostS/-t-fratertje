# Ledenadministratie Vogelvereniging 't Fratertje

Webapplicatie voor het beheren van leden, adressen, lidsoorten, NBvV-gegevens en kweeknummers van vogelvereniging 't Fratertje. Gebouwd met Laravel volgens het MVC-patroon, met Eloquent, migrations, validatie en authenticatie.

> **Let op:** de opdracht is op een aantal punten aangescherpt ten opzichte van eerdere versies. Zie [Bedrijfsregels](#bedrijfsregels) voor de belangrijkste wijziging (kweeknummer geldt alleen voor jeugd- en volwassen leden, niet voor gastleden).

> **Status:** in ontwikkeling. Dit README wordt per fase aangevuld naarmate er functionaliteit bijkomt (zie het stappenplan in `docs/`).

---

## Inhoud

- [Bedrijfsregels](#bedrijfsregels)
- [Gebruikte tools](#gebruikte-tools)
- [Vereisten](#vereisten)
- [Installatie](#installatie)
- [Omgevingsvariabelen (.env)](#omgevingsvariabelen-env)
- [Database opzetten](#database-opzetten)
- [Test-inloggegevens](#test-inloggegevens)
- [Applicatie starten](#applicatie-starten)
- [Tests draaien](#tests-draaien)
- [Projectstructuur](#projectstructuur)
- [Belangrijkste functionaliteit](#belangrijkste-functionaliteit)

---

## Bedrijfsregels

Samenvatting van de kernregels waar de applicatie zich aan moet houden (zie de volledige opdracht voor details):

- **Lidsoorten:** volwassen lid (vanaf 18 jaar), jeugdlid (tot 18 jaar), gastlid (alle leeftijden).
- **NBvV-lidmaatschap en kweeknummer:**
  - Een **jeugdlid** en een **volwassen lid** zijn automatisch NBvV-lid en hebben dus **verplicht** een uniek kweeknummer.
  - Een **gastlid** kan géén NBvV-lid zijn en heeft dus **geen** kweeknummer.
  - Een kweeknummer hoort bij maximaal één lid en moet uniek zijn.
- **Contributie:** volwassen lid €36,00/jaar, jeugdlid €18,00/jaar, gastlid €18,00/jaar. Een jeugdlid dat in het lopende jaar 18 wordt, betaalt dat jaar nog het jeugdtarief (geen correctie achteraf).
- **Ingangsdatum lidmaatschap:** na aanmelding duurt het 3 weken voordat iemand daadwerkelijk lid wordt (ingangsdatum = de eerste van de eerstvolgende maand na die 3 weken). De contributie wordt naar rato van de resterende maanden van dat jaar berekend.
- **Aanmelden:** verloopt via een digitaal formulier. Gegevens komen direct in de database, maar in **quarantaine** totdat de administratie ze verwerkt (met signalering per e-mail of anderszins). Een checkbox ("ik verklaar dat deze gegevens juist zijn") dient als digitale handtekening.
- **Afmelden:** verloopt eveneens via een digitaal formulier en wordt direct verwerkt; de administratie ontvangt een signaal. Het lid krijgt de contributie voor de resterende maanden terug, berekend volgens dezelfde 3-weken-regel als bij aanmelden.
- **Soft-deletes:** er wordt nooit iets definitief verwijderd. "Verwijderde" leden en kweeknummers blijven zichtbaar/op te vragen via een archiefweergave.
- **Prijswijzigingen:** een aanpassing van de contributie heeft geen effect op het lopende jaar of op de historie; wijzigingen worden vooraf aangekondigd en gelden pas vanaf het volgende jaar.

---

## Gebruikte tools

- **Laravel** (zie `composer.json` voor exacte versie)
- **PHP** (zie `composer.json` voor minimale versie)
- **Laravel Herd** — lokale ontwikkelomgeving (PHP, Nginx, lokale domeinen)
- **MySQL** — database
- **Composer** — PHP dependency management
- **npm** — front-end dependencies (indien van toepassing)
- **Git** — versiebeheer

---

## Vereisten

Voordat je begint, zorg dat het volgende geïnstalleerd is:

- [Laravel Herd](https://herd.laravel.com/) (bevat PHP en Nginx)
- [Composer](https://getcomposer.org/)
- [Node.js en npm](https://nodejs.org/) (voor front-end assets, indien gebruikt)
- MySQL (via Herd's ingebouwde database-toevoeging, of een losse MySQL-installatie)
- Git

---

## Installatie

1. **Clone de repository**

   ```bash
   git clone <repository-url>
   cd ledenadministratie
   ```

2. **Plaats het project in de Herd-map (of parkeer de map in Herd)**

   Als je de repository buiten je standaard Herd-map hebt gecloned, voeg het pad toe via Herd (*Herd → Sites → Add Path*), of clone direct in de map die Herd al in de gaten houdt (standaard `~/Herd`).

3. **Installeer PHP-dependencies**

   ```bash
   composer install
   ```

4. **Installeer front-end dependencies (indien van toepassing)**

   ```bash
   npm install
   npm run build
   ```

5. **Maak het `.env`-bestand aan**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

---

## Omgevingsvariabelen (.env)

Vul in `.env` in ieder geval de volgende waarden in. **Neem nooit echte wachtwoorden op in de broncode of in commits.**

```env
APP_NAME="Ledenadministratie 't Fratertje"
APP_URL=http://ledenadministratie.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ledenadministratie
DB_USERNAME=root
DB_PASSWORD=
```

> Met Herd kun je via het Herd-menu (*Database*) eenvoudig een MySQL-database aanmaken en de bijbehorende gegevens hierboven invullen. Pas `APP_URL` aan naar het domein dat Herd aan het project toekent (bijv. `http://ledenadministratie.test`).

---

## Database opzetten

Migrations bouwen de volledige databasestructuur op, seeders vullen de vaste basisgegevens (lidsoorten en een testgebruiker).

```bash
php artisan migrate:fresh --seed
```

Dit maakt onder andere de volgende tabellen aan: `users`, `member_types`, `addresses`, `members`, `breeding_numbers`.

---

## Test-inloggegevens

Na het seeden kun je inloggen met:

| Veld       | Waarde                     |
|------------|-----------------------------|
| E-mailadres | `admin@fratertje.test`     |
| Wachtwoord  | `password`                 |

> *Deze gegevens zijn alleen bedoeld voor lokaal testen/beoordelen en mogen niet in een productieomgeving gebruikt worden.*

---

## Applicatie starten

Met Herd draait de site automatisch op het toegewezen lokale domein, bijvoorbeeld:

```
http://ledenadministratie.test
```

Wil je in plaats daarvan de ingebouwde PHP-server gebruiken:

```bash
php artisan serve
```

De applicatie is dan bereikbaar op `http://127.0.0.1:8000`.

---

## Tests draaien

```bash
php artisan test
```

---

## Projectstructuur

Korte toelichting op de belangrijkste mappen:

```
app/
  Events/          Domeinevents, bv. voor het berekenen van contributie
  Helpers/          Helper-functies (bv. contributieberekening)
  Http/
    Controllers/    Dunne controllers, verwijzen naar Form Requests en Eloquent
    Requests/        Form Requests met validatieregels
  Listeners/         Listeners die de zwaardere logica achter events afhandelen
  Models/            Eloquent-modellen en hun relaties
database/
  migrations/        Opbouw van de databasestructuur
  seeders/           Vaste basisgegevens (lidsoorten, testgebruiker)
resources/views/     Blade-views
docs/                ERD, stappenplan en overige documentatie
```

---

## Belangrijkste functionaliteit

- Inloggen/uitloggen met afscherming van de ledenadministratie via middleware.
- Ledenoverzicht met zoeken en filteren op lidsoort en actieve status.
- Volledige CRUD voor leden (inclusief adres en NBvV-/kweeknummer), lidsoorten en ringnummers.
- Soft-deletes: verwijderde leden blijven inzichtbaar in plaats van definitief verwijderd te worden.
- Automatische contributieberekening (via een Event/Listener) op basis van lidsoort, geboortedatum en aanmelddatum.
- Publiek aanmeld- en afmeldformulier; nieuwe aanmeldingen komen eerst in quarantaine en worden gesignaleerd aan de administratie.

---

## Licentie

Dit project is gemaakt in het kader van een schoolopdracht en niet bedoeld voor productiegebruik.