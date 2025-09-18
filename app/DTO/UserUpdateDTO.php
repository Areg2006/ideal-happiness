<?php

namespace App\DTO;

class UserUpdateDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $role;
    public int $balance;

    public function __construct(int $id, string $name, string $email, string $role, int $balance)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->balance = $balance;
    }
}
