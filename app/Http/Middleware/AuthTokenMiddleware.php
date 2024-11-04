<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el token del encabezado Authorization
        $token = $request->bearerToken();

        // Verificar si el token está presente
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401); // Error si no se proporciona el token
        }

        // Buscar el token en la base de datos
        $tokenData = DB::table('tokens')->where('token', $token)->first();

        // Verificar si el token existe y no ha expirado
        if (!$tokenData || $tokenData->expires_at < now()) {
            return response()->json(['error' => 'Token is invalid or has expired'], 401); // Error si el token es inválido o ha expirado
        }

        // Si el token es válido, continuar con la solicitud
        return $next($request);
    }
}
