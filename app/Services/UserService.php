<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserService
{
    protected UserRepository $userRepo;

    public function __construct( UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function listUser()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }

        if ($user->role === 'admin') {
            $users = $this->userRepo->getAll();
            return response()->json($users, 200);
        }

        if ($user->role === 'user') {
            $users = $this->userRepo->getByUserId($user->id);
            return response()->json($users, 200);
        }

        if ($user->role === 'partnership') {
            return response()->json(['message' => 'У вас нет доступа'], 403);
        }

        return response()->json(['message' => 'Роль не определена'], 400);
    }


    public function createUser(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user,partnership',
        ]);
        $validated['user_id'] = $user->id;
        return $this->userRepo->create($validated);
    }

    public function updateUser(Request $request, int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'клиент не найден'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user,partnership',
        ]);

        return $this->userRepo->update($user, $request->all());
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'клиент не найден'], 404);
        }

        $this->userRepo->delete($user);
        return response()->json(['message' => 'клиент удалён']);
    }

    public function showUser(int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'клиент не найден'], 404);
        }
        return $user->load('products');
    }

}
