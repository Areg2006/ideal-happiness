<?php

namespace App\DTO;

class ProductStoreDTO
{
    public string $name;
    public int $price;
    public string $description;
    public int $categoryId;

    public function __construct(string $name, int $price, string $description, int $categoryId)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->categoryId = $categoryId;
    }
}
