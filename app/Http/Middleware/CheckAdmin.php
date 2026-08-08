<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario tiene sesión iniciada y su rol es 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Lo dejamos pasar a la página que pidió
        }

        // 2. Si es residente o intruso, lo devolvemos a la pantalla de inicio
        return redirect('/home')->with('error', 'Acceso denegado: No tienes permisos de administrador.');
    }
}