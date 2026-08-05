<?php

use App\Http\Controllers\TurniejController;
use App\Http\Controllers\ZawodnikController;
use App\Http\Controllers\ZgloszenieController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // only for authenticated and verified users
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource('turnieje', TurniejController::class)
        ->parameters(['turnieje' => 'turniej']) // zmiana nazwy parametru na l.poj.
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    Route::resource('turnieje.zgloszenia', ZgloszenieController::class)
        ->parameters([
            'turnieje' => 'turniej',
            'zgloszenia' => 'zgloszenie',
        ])
        ->only(['index', 'create', 'store']);
    
    Route::post('turnieje/{turniej}/zgloszenia/{zgloszenie}/approve', [ZgloszenieController::class, 'approve'])->name('turnieje.zgloszenia.approve');
    Route::post('turnieje/{turniej}/zgloszenia/{zgloszenie}/reject', [ZgloszenieController::class, 'reject'])->name('turnieje.zgloszenia.reject');

    Route::resource('turnieje.zawodnicy', ZawodnikController::class)
        ->parameters([
            'turnieje' => 'turniej',
        ])
        ->only(['index']);
});

require __DIR__.'/settings.php';
