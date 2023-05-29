<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{


    public function register(RegisterRequest $request)
    {
        // Crear al usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);


        // Generar Token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // Intentar autenticar al usuaario
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'las credenciales ingresadas son incorrectas.'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'not create token'
            ], 500);
        }

        return response()->json(compact('token'));
    }
}
