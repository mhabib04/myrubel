<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/program/{slug}', [PageController::class, 'program'])->name('program.show');
Route::get('/event/{slug}', [PageController::class, 'event'])->name('event.show');
Route::get('/info/{slug}', [PageController::class, 'info'])->name('info.show');
