<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        // Datos del log
        $logData = [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => $request->all(),
        ];

        // Log de entrada
        Log::channel('custom')->info('Request received:', $logData);

        // Procesar la solicitud
        $response = $next($request);

        // Verificar si estamos en producción y si LOG_OUTPUT es verdadero
        if (config('app.env') === 'local') {
            // Log de salida
            Log::channel('custom')->info('Response sent:', [
                'status' => $response->status(),
                'content' => $response->getContent(),
            ]);
        }

        return $response;
    }
}
