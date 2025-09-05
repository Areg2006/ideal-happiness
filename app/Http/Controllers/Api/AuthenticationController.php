<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller

 /* public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $response = Http::asForm()->post('http://localhost:8001' . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);

        return $response->json();
    }*/
{
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('appToken')->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Не удалось пройти аутентификацию.',
        ], 401);
    }
    public function destroy(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Выход выполнен успешно',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Пользователь не авторизован',
        ], 401);
    }
}


