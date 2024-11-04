<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Token; // Asegúrate de que tienes el modelo Token creado
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }

        // Genera el token en SHA-1
        $tokenString = sha1($user->email . now() . rand(200, 500));

        // Define el tiempo de expiración (ejemplo: 1 hora)
        $expiresAt = now()->addHour();

        $user->remember_token = $tokenString;
        $user->save();

        $token = Token::create([
            'user_id' => $user->id,
            'token' => $tokenString,
            'expires_at' => $expiresAt,
        ]);

        return response()->json(['token' => $tokenString]);
    }
}
