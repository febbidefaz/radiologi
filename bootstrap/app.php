<?php

use App\Http\Middleware\CekLoginRad;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('rawat-inap')
                ->name('rawatinap.')
                ->group(base_path('routes/rawatinap.php'));

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('lain')
                ->name('lain.')
                ->group(base_path('routes/lain.php'));

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('rad')
                ->name('rad')
                ->group(base_path('routes/rad.php'));

            Route::middleware('web')
                ->prefix('userrad')
                ->group(base_path('routes/userrad.php'));

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('pulang')
                ->name('pulang.')
                ->group(base_path('routes/pulang.php'));

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('rawat-jalan')
                ->name('rawatjalan.')
                ->group(base_path('routes/rawatjalan.php'));

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('igd')
                ->name('igd.')
                ->group(base_path('routes/igd.php'));
            
            Route::middleware(['web', 'cekloginrad'])
                ->prefix('notifsp')
                ->name('notifsp.')
                ->group(base_path('routes/notifsp.php'));
            
            Route::middleware(['web', 'cekloginrad'])
                ->prefix('sp')
                ->name('sp.')
                ->group(base_path('routes/sp.php')); 

            Route::middleware(['web', 'cekloginrad'])
                ->prefix('satusehat')
                ->name('satusehat.')
                ->group(base_path('routes/satusehat.php'));     
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'cekloginrad' => CekLoginRad::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
