<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckNotBlocked
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isBlocked()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['phone' => 'Votre compte a été bloqué. Contactez l\'administrateur.']);
        }
        return $next($request);
    }
}

