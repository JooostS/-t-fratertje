<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BreedingNumberController;
use App\Http\Controllers\CancellationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContributionRateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberTypeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

// Publiek: informatie en de digitale aan- en afmeldformulieren.
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('informatie', [PageController::class, 'info'])->name('info');
Route::get('contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::middleware('throttle:10,1')->group(function () {
    Route::get('aanmelden', [SignupController::class, 'create'])->name('signup.create');
    Route::post('aanmelden', [SignupController::class, 'store'])->name('signup.store');
    Route::get('afmelden', [CancellationController::class, 'create'])->name('cancellation.create');
    Route::post('afmelden', [CancellationController::class, 'store'])->name('cancellation.store');
});

Route::middleware('guest')->group(function () {
    Route::get('inloggen', [LoginController::class, 'create'])->name('login');
    Route::post('inloggen', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

// Ledenadministratie: alleen voor ingelogde gebruikers.
Route::middleware('auth')->group(function () {
    Route::post('uitloggen', [LoginController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::patch('leden/{member}/herstellen', [MemberController::class, 'restore'])->withTrashed()->name('members.restore');
    Route::patch('leden/{member}/goedkeuren', [MemberController::class, 'approve'])->name('members.approve');
    Route::resource('leden', MemberController::class)
        ->parameters(['leden' => 'member'])
        ->names('members')
        ->withTrashed(['show']);

    Route::resource('lidsoorten', MemberTypeController::class)
        ->parameters(['lidsoorten' => 'member_type'])
        ->names('member-types')
        ->except('show');
    Route::post('lidsoorten/{member_type}/tarieven', [ContributionRateController::class, 'store'])->name('contribution-rates.store');

    Route::patch('kweeknummers/{breeding_number}/herstellen', [BreedingNumberController::class, 'restore'])->withTrashed()->name('breeding-numbers.restore');
    Route::resource('kweeknummers', BreedingNumberController::class)
        ->parameters(['kweeknummers' => 'breeding_number'])
        ->names('breeding-numbers')
        ->except('show');

    Route::get('facturen', [InvoiceController::class, 'index'])->name('invoices.index');
});
