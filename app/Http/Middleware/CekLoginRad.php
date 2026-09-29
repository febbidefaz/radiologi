<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekLoginRad
{
    public function handle(
        Request $request,
        Closure $next
    ) {

        if (!session('userrad_id')) {

            return redirect()
                ->route('rad.login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }

        return $next($request);
    }
}