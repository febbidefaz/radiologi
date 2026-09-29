<?php

use App\Http\Controllers\AuthRadController;
use App\Http\Controllers\UserRadController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthRadController::class, 'showLogin'])
    ->name('rad.login');

Route::post('/login', [AuthRadController::class, 'login'])
    ->name('rad.login.post');

Route::middleware('cekloginrad')->group(function () {

    Route::get('/', function () {
        return redirect('/rawat-inap');
    })->name('rad.home');

    Route::post('/logout', [AuthRadController::class, 'logout'])
        ->name('rad.logout');

    Route::post('/profile/password/update', [AuthRadController::class, 'updatePassword'])
        ->name('rad.profile.password.update');

    Route::prefix('user')->name('userrad.')->group(function () {
        Route::get('/', [UserRadController::class, 'index'])->name('index');
        Route::post('/store', [UserRadController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserRadController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserRadController::class, 'destroy'])->name('destroy');
    });
});