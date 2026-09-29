<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawatInapController;

Route::get('/', [RawatInapController::class, 'index'])
    ->name('index');

Route::get('/data', [RawatInapController::class, 'data'])
    ->name('data');

Route::get('/{id}', [RawatInapController::class, 'detail'])
    ->name('detail');

    // Cek Sep dan BPJS
Route::get('/sep/detail', [RawatInapController::class, 'sepDetail'])->name('sep.detail');