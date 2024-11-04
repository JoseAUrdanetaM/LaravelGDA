<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Token;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validar los datos de entrada para el inicio de sesión
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Buscar al usuario por su email
            $user = User::where('email', $validatedData['email'])->first();

            // Verificar las credenciales del usuario
            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Generar el token en SHA-1
            $tokenString = sha1($user->email . now() . rand(200, 500));

            // Definir el tiempo de expiración del token (En este caso se agrega 1 hora)
            $expiresAt = now()->addHour();

            // Guardar el token en la tabla de usuarios
            $user->remember_token = $tokenString;
            $user->save();

            // Crear el registro del token en la tabla de tokens
            Token::create([
                'user_id' => $user->id,
                'token' => $tokenString,
                'expires_at' => $expiresAt,
            ]);

            // Responder con éxito y el token generado
            return response()->json([
                'success' => true,
                'msg' => 'User logged in',
                'token' => $tokenString,
            ]);
        } catch (ValidationException $e) {
            // Captura de errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(), // Detalles de los errores de validación
            ], 422);
        } catch (\Exception $e) {
            // Captura de cualquier otro error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
