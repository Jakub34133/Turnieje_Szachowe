<?php

use App\Http\Controllers\PartiaController;
use App\Http\Controllers\RundaController;
use App\Http\Controllers\TurniejController;
use App\Http\Controllers\TytulController;
use App\Http\Controllers\ZawodnikController;
use App\Http\Controllers\ZgloszenieController;
use Illuminate\Support\Facades\Route;

// Dla wszystkich użytkowników oraz gości
Route::get('/', [TurniejController::class, 'index'])->name('home');
Route::get('turnieje', [TurniejController::class, 'index'])->name('turnieje.index');
Route::get('turnieje/{turniej}/show', [TurniejController::class, 'show'])->name('turnieje.show');
Route::get('turnieje/{turniej}/wyniki', [ZawodnikController::class, 'index'])->name('zawodnicy.index');
Route::get('turnieje/{turniej}/rundy/{runda?}', [RundaController::class, 'show'])->name('rundy.show');

// Publiczne profile użytkowników
Route::get('zawodnicy/{zawodnik}', [ZawodnikController::class, 'show'])->name('zawodnicy.show');
Route::get('zawodnicy/{zawodnik}/tytuly', [TytulController::class, 'index'])->name('tytuly.index');

Route::middleware(['auth', 'verified'])->group(function () {
    // Tylko dla zalogowanych i zweryfikowanych użytkowników
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Zarządzanie turniejami
    Route::get('turnieje/create', [TurniejController::class, 'create'])->name('turnieje.create');
    Route::post('turnieje/store', [TurniejController::class, 'store'])->name('turnieje.store');
    Route::get('turnieje/{turniej}/edit', [TurniejController::class, 'edit'])->name('turnieje.edit');
    Route::put('turnieje/{turniej}/update', [TurniejController::class, 'update'])->name('turnieje.update');
    Route::delete('turnieje/{turniej}/destroy', [TurniejController::class, 'destroy'])->name('turnieje.destroy');

    // Zgłoszenia wymagające zalogowanego użytkownika
    Route::get('turnieje/{turniej}/zgloszenia', [ZgloszenieController::class, 'index'])->name('turnieje.zgloszenia.index');
    Route::get('turnieje/{turniej}/zgloszenia/create', [ZgloszenieController::class, 'create'])->name('turnieje.zgloszenia.create');
    Route::post('turnieje/{turniej}/zgloszenia/store', [ZgloszenieController::class, 'store'])->name('turnieje.zgloszenia.store');
    Route::post('turnieje/{turniej}/zgloszenia/{zgloszenie}/approve', [ZgloszenieController::class, 'approve'])->name('turnieje.zgloszenia.approve');
    Route::post('turnieje/{turniej}/zgloszenia/{zgloszenie}/reject', [ZgloszenieController::class, 'reject'])->name('turnieje.zgloszenia.reject');

    // Akcje modyfikujące rundy i partie
    Route::post('turnieje/{turniej}/rundy/{runda}/create', [RundaController::class, 'store'])->name('rundy.store');
    Route::patch('turnieje/{turniej}/rundy/{runda}/begin', [RundaController::class, 'begin'])->name('rundy.begin');
    Route::patch('turnieje/{turniej}/rundy/{runda}/finish', [RundaController::class, 'finish'])->name('rundy.finish');

    Route::patch('turnieje/{turniej}/rundy/{runda}/partie/{partia}', [PartiaController::class, 'update'])->name('partie.update');
});

require __DIR__.'/settings.php';
