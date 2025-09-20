<?php

namespace App\DTO;

class UserStoreDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public int $balance;

    public function __construct(string $name, string $email, string $password, string $role, int $balance)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->balance = $balance;
    }
}
