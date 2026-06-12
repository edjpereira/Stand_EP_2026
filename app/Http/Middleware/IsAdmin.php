<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se o utilizador estiver logado e for admin, o barco segue viagem
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Se for um funcionário comum (employee), barra o acesso e avisa
        return redirect()->route('sales.index')
            ->with('error', 'Acesso negado! Apenas administradores podem editar ou anular vendas.');
    }
}
