<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // Verifica se o utilizador está autenticado e se o role é 'admin'
    if ($request->user() && $request->user()->role === 'admin') {
        return $next($request);
    }

    // Se não for admin, manda-o para a home com um erro
    return redirect('home')->with('error', 'Não tens permissão para aceder a esta área.');
}
}
