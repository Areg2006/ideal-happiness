<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;


class   CheckUserRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        if ($user->role === 'partnership') {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        return $next($request);
    }
}
