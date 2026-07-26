<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Vérifie que l'utilisateur connecté n'est pas bloqué.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->statut === 'bloque') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte a été bloqué. Veuillez contacter un administrateur.');
        }

        return $next($request);
    }
}