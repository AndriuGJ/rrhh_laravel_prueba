<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $esperado = (string) config('app.qr_api_key', '');
        $recibido = (string) $request->header('X-Api-Key', '');

        if ($esperado === '' || $recibido === '' || ! hash_equals($esperado, $recibido)) {
            return response()->json([
                'ok' => false,
                'error' => 'API Key inválida',
                'codigo' => 'INVALID_API_KEY',
            ], 401);
        }

        return $next($request);
    }
}
