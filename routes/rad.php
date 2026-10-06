<?php

use App\Http\Controllers\RadController;
use App\Http\Controllers\RawatInapController;
use Illuminate\Support\Facades\Route;

Route::post('/update-pxrs/{id}', [RawatInapController::class, 'updatePxRS'])
    ->name('update.pxrs');

Route::get('/print/{idLab}', [RadController::class, 'print'])
    ->name('print');  

Route::get('/printkwitansi/{idLab}', [RadController::class, 'printkwitansi'])
    ->name('printkwitansi');

Route::get('/cari-pasien-id', [RadController::class, 'cariPasien'])->name('cari.pasien.id'); 