<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function find(int $id)
    {
        return User::find($id);
    }

    public function getByUserId(int $userId)
    {
        return User::where('id', $userId)->get();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete($user)
    {
        $user->delete();
    }

    public function save(User $user)
    {
        $user->save();
    }

    public function getAll()
    {
        return User::all();
    }
}
