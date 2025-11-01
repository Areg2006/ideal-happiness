<?php

namespace App\DTO;

class CategoryStoreDTO
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
