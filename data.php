<?php

$is_auth = rand(0, 1);

$user_name = 'Natalia'; // укажите здесь ваше имя


$categories = [
    "boards" => "Доски и лыжи",
    "attachment" => "Крепления",
    "boots" => "Ботинки",
    "clothing" => "Одежда",
    "tools" => "Инструменты",
    "other" => "Разное"
];

$goods = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "start_price" => 10999,
        "image" => "img/lot-1.jpg",
        "expiration_date" => "2023-06-15"
    ],
    [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "start_price" => 159999,
        "image" => "img/lot-2.jpg",
        "expiration_date" => "2023-06-16"
    ],
    [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "start_price" => 8000,
        "image" => "img/lot-3.jpg",
        "expiration_date" => "2023-06-17"
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "start_price" => 10999,
        "image" => "img/lot-4.jpg",
        "expiration_date" => "2023-06-18"
    ],
    [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => "Одежда",
        "start_price" => 7500,
        "image" => "img/lot-5.jpg",
        "expiration_date" => "2023-06-19"
    ],
    [
        "name" => "Маска Oakley Canopy",
        "category" => "Разное",
        "start_price" => 5400,
        "image" => "img/lot-6.jpg",
        "expiration_date" => "2023-06-20"
    ]
];