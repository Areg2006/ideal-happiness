<?php

namespace App\DTO;

class ProductUpdateDTO
{
    public int $id;
    public string $name;
    public int $price;
    public string $description;
    public int $categoryId;


    public function __construct(int $id, $name, int $price, string $description, int $categoryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->categoryId = $categoryId;
    }

}
