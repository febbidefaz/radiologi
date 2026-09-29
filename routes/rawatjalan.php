<?php

use App\Http\Controllers\RawatJalanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RawatJalanController::class, 'index']) ->name('index');
Route::get('/data', [RawatJalanController::class, 'data'])->name('data');  
Route::get('/{id}', [RawatJalanController::class, 'detail'])->name('detail');