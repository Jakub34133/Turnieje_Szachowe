<?php

use App\Http\Controllers\TurniejController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // only for authenticated and verified users
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource('turnieje', TurniejController::class)
        ->parameters(['turnieje' => 'turniej']) // zmiana nazwy parametru na l.poj.
        ->only(['index', 'create', 'store', 'show']);

        

});

require __DIR__.'/settings.php';
