<?php

use App\Http\Controllers\SatusehatController;
use Illuminate\Support\Facades\Route;

Route::get('/cek-config', function () {
    return response()->json([
        'client_id' => config('satu-sehat.satusehat.client_id'),
        'organization_id' => config('satu-sehat.satusehat.organization_id'),
        'auth_url' => config('satu-sehat.satusehat.auth_url'),
        'fhir_url' => config('satu-sehat.satusehat.fhir_url'),
    ]);
});

Route::get('/encounter/{therapyID}', [SatusehatController::class, 'kirimEncounter']);

Route::get('/ihs-pasien/{therapyID}', [SatusehatController::class, 'getIhsPasien']
    )->name('ihs-pasien');

Route::get('/service-request/{therapyID}', [SatusehatController::class, 'kirimServiceRequest']
    )->name('service-request');

Route::get('/observation/{therapyID}', [SatusehatController::class, 'kirimObservation']
    )->name('observation');