<?php

namespace App\Services;

use App\DTO\UserStoreDTO;
use App\DTO\UserUpdateDTO;
use App\Models\User;
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

    public function createUser(UserStoreDTO $dto): User
    {
        $authUser = Auth::user();
        if (!$authUser) {
            abort(401, 'Вы не авторизованы');
        }

        return User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => bcrypt($dto->password),
            'role'     => $dto->role,
            'balance'  => $dto->balance,
        ]);
    }

    public function updateUser(int $id, UserUpdateDTO $dto): ?User
    {
        $authUser = Auth::user();
        if (!$authUser) {
                abort(401, 'Вы не авторизованы');
        }

        $user = User::find($id);
        if (!$user) {
            abort(404, 'Пользователь не найден');
        }

        $user->update([
            'id'       => $dto->id,
            'name'     => $dto->name,
            'email'    => $dto->email,
            'role'     => $dto->role,
            'balance'  => $dto->balance,
        ]);

        return $user;
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
