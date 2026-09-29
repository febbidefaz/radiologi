<?php

use App\Http\Controllers\RawatjalanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RawatjalanController::class, 'index']) ->name('index');
Route::get('/data', [RawatjalanController::class, 'data'])->name('data');  
Route::get('/{id}', [RawatjalanController::class, 'detail'])->name('detail');