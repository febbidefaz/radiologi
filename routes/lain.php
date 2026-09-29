<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RawatInapController;

Route::post('/insert', [RawatInapController::class, 'insertLain'])
    ->name('insert');

Route::post('/update', [RawatInapController::class, 'updateLain'])
    ->name('update');

Route::post('/delete', [RawatInapController::class, 'deleteLain'])
    ->name('delete');