<?php

namespace App\TryCatchLesson;

class Division
{
    public function divide(int $a, int $b): float
    {
        if ($b === 0) {
            throw new \Exception("Деление на ноль недопустимо");
        }

        return $a / $b;
    }
}

try {
    $div = new Division();
    echo $div->divide(10, 2);
    echo "\n";
    echo $div->divide(10, 0);
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
} finally {
    echo "Блок finally<br>";
}
echo "Конец работы программы";

