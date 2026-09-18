<?php

use Illuminate\Support\Facades\Schedule;

// Contributie wordt jaarlijks op 1 januari gefactureerd; prijswijzigingen gelden dan voor het nieuwe jaar.
Schedule::command('invoices:generate')->yearlyOn(1, 1, '06:00');
