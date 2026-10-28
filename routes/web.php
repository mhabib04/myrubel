<?php

use App\Http\Controllers\LiteracyController;
use App\Http\Controllers\MotivationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Models\Work;
use Illuminate\Support\Facades\Route;

// Website subdomain harus didaftarkan sebelum route website utama.

// My Rubel Literacy Center (subdomain)
Route::domain(config('sites.literacy_domain'))->name('literacy.')->group(function () {
    $types = array_keys(Work::TYPES);

    Route::get('/', [LiteracyController::class, 'home'])->name('home');
    Route::get('/program/{slug}', [LiteracyController::class, 'program'])->name('program.show');
    Route::get('/event/{slug}', [LiteracyController::class, 'event'])->name('event.show');
    Route::get('/karya/{type}', [LiteracyController::class, 'works'])->whereIn('type', $types)->name('karya.index');
    Route::get('/karya/{type}/{slug}', [LiteracyController::class, 'work'])->whereIn('type', $types)->name('karya.show');
    Route::get('/sitemap.xml', [SitemapController::class, 'literacy'])->name('sitemap');
});

// My Rubel Motivation & Outbound Center (subdomain)
Route::domain(config('sites.motivation_domain'))->name('motivation.')->group(function () {
    Route::get('/', [MotivationController::class, 'home'])->name('home');
    Route::get('/program/{slug}', [MotivationController::class, 'program'])->name('program.show');
    Route::get('/event/{slug}', [MotivationController::class, 'event'])->name('event.show');
    Route::get('/sitemap.xml', [SitemapController::class, 'motivation'])->name('sitemap');
});

// Website utama: Bimbel My Rubel
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/program/{slug}', [PageController::class, 'program'])->name('program.show');
Route::get('/event/{slug}', [PageController::class, 'event'])->name('event.show');
Route::get('/info/{slug}', [PageController::class, 'info'])->name('info.show');
Route::get('/sitemap.xml', [SitemapController::class, 'bimbel'])->name('sitemap');
