<?php

interface Drivable
{
    public function drive(): void;
}

trait Loggable
{
    public function log(string $message): void
    {
        echo "[LOG]" . $message . "<br>";
    }
}

class Car implements Drivable
{
    use Loggable;

    public string $brand;
    private int $speed;

    public function __construct(string $brand, int $speed)
    {
        $this->brand = $brand;
        $this->speed = $speed;
    }

    public function drive(): void
    {
        echo "The {$this->brand} is driving at {$this->speed} km/h.<br>";
    }

    public function accelerate(int $value): void
    {
        $this->speed += $value;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }
}

class Bike extends Car
{
    use Loggable;

    public int $price;

    public function __construct(string $brand, int $speed, int $price)
    {
        parent::__construct($brand, $speed);
        $this->price = $price;
    }

    public function drive(): void
    {
        echo "The bike with {$this->price} wheels is driving at 80 km/h.<br>";
    }
}

class Bus extends Car
{
    use Loggable;

    public int $size;

    public function __construct(string $brand, int $speed, int $size)
    {
        parent::__construct($brand, $speed);
        $this->size = $size;
    }
}

class ElectricCar extends Car
{
    use Loggable;

    public int $batteryLevel;

    public function __construct(string $brand, int $speed, int $batteryLevel)
    {
        parent::__construct($brand, $speed);
        $this->batteryLevel = $batteryLevel;
    }
}

$vehicles = [new Car("BMW", 100),
    new Bike("Audi", 80, 250),
    new ElectricCar("Tesla", 50, 10),
    new Bus("School_bus", 60, 500)];
foreach ($vehicles as $v) {
    $v->drive();
}

final class User
{
    private string $name;
    private int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
        return "User {$this->name} is created";
    }

    final public function greet(): void
    {
        echo "Hello my name is  {$this->name}, and I am {$this->age} years old.<br>";
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return "User (name:{$this->name}, age:{$this->age})";
    }

    public function __destruct()
    {
        echo "User {$this->name} is being destroyed.<br>";
    }
}

$user = new User("Bob", 16);
$user->greet();

echo $user . "<br>";

