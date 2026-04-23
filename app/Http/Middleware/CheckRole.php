<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Roles permitidos separados por coma
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Validar si el usuario está autenticado y si su rol está en los permitidos
        if (!Auth::check() || !in_array(Auth::user()->rol, $roles)) {
            // Retorna respuesta de error 403
            abort(403, 'Unauthorized / Access Denied');
        }

        return $next($request);
    }
}
