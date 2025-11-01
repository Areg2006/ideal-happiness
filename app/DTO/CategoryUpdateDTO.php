<?php

namespace App\DTO;

class CategoryUpdateDTO
{
    public int $id;
    public string $name;

    public function __construct(int $id, String $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
