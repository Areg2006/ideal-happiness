<?php

abstract class Animal
{
    abstract function makeSound(): void;
}

class Dog extends Animal
{
    function makeSound(): void
    {
        echo "Gaf-gaf";
    }
}

class Cat extends Animal
{
    function makeSound(): void
    {
        echo "Meow-meow";
    }
}

class Bird extends Animal
{
    function makeSound(): void
    {
        echo "Civ-civ";
    }
}

$animals = [new Dog(), new Cat(), new Bird()];
foreach ($animals as $animal) {
    echo $animal->makeSound();
}
