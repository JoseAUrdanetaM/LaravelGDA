<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        // Datos del log
        $logData = [
            //En XAMPP este puede detectarlo como 127.0.0.1
            'ip' => $request->ip(), // Obtener la dirección IP del cliente
            'url' => $request->fullUrl(), // Obtener la URL completa de la solicitud
            'method' => $request->method(), // Obtener el método HTTP de la solicitud (GET, POST, etc.)
            'input' => $request->all(), // Obtener todos los datos de entrada
        ];

        // Log de entrada
        Log::channel('custom')->info('Request received:', $logData); // Registrar la solicitud recibida

        // Procesar la solicitud
        $response = $next($request);

        // Registrar la respuesta solo en entornos locales
        if (App::environment('local')) {
            Log::channel('custom')->info('Response sent:', [
                'status' => $response->status(),
                'content' => $response->getContent(),
            ]);
        }

        return $response;
    }
}
