<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
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

    public function createUser(array $data)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }

        $data['user_id'] = $user->id;

        return $this->userRepo->create($data);
    }

    public function updateUser(array $data, int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }

        return $this->userRepo->update($user, $data);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }

        $this->userRepo->delete($user);
        return response()->json(['message' => 'Клиент удалён']);
    }

    public function showUser(int $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return response()->json(['message' => 'Клиент не найден'], 404);
        }

        return $user->load('products');
    }
}
