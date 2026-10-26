<?php

use App\Http\Controllers\LiteracyController;
use App\Http\Controllers\PageController;
use App\Models\Work;
use Illuminate\Support\Facades\Route;

// My Rubel Literacy Center (subdomain). Harus didaftarkan sebelum route website utama.
Route::domain(config('sites.literacy_domain'))->name('literacy.')->group(function () {
    $types = array_keys(Work::TYPES);

    Route::get('/', [LiteracyController::class, 'home'])->name('home');
    Route::get('/program/{slug}', [LiteracyController::class, 'program'])->name('program.show');
    Route::get('/event/{slug}', [LiteracyController::class, 'event'])->name('event.show');
    Route::get('/karya/{type}', [LiteracyController::class, 'works'])->whereIn('type', $types)->name('karya.index');
    Route::get('/karya/{type}/{slug}', [LiteracyController::class, 'work'])->whereIn('type', $types)->name('karya.show');
});

// Website utama: Bimbel My Rubel
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/program/{slug}', [PageController::class, 'program'])->name('program.show');
Route::get('/event/{slug}', [PageController::class, 'event'])->name('event.show');
Route::get('/info/{slug}', [PageController::class, 'info'])->name('info.show');
