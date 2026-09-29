<?php

use App\Http\Controllers\PulangController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PulangController::class, 'index']) ->name('index');
Route::get('/data', [PulangController::class, 'data'])->name('data');  
Route::get('/{id}', [PulangController::class, 'detail'])->name('detail');