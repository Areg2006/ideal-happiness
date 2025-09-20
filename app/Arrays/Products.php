<?php

$products = [
    [
        'name' => 'Телефон',
        'price' => 500,
        'category' => [
            'name' => 'Электроника'
        ]
    ],
    [
        'name' => 'Книга',
        'price' => 20,
        'category' => [
            'name' => 'Книги'
        ]
    ]
];

foreach ($products as $product) {
    echo $product['category']['name'] . "<br>";
}
