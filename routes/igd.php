<?php

use App\Http\Controllers\IGDController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IGDController::class, 'index']) ->name('index');
Route::get('/data', [IGDController::class, 'data'])->name('data');  
Route::get('/{id}', [IGDController::class, 'detail'])->name('detail');