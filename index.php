<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");
require_once("models.php");

if ($connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
//    отправляем SQL-запрос для получения списка новых лотов
    $result_lots = mysqli_query($connection, get_list_lots());
    if (!$result_lots) {
        print("Ошибка MySQL: " . mysqli_error($connection));
    } else {
        $goods = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
    };
//    отправляем запрос для получения списка категорий
    $sql_query = "SELECT symbol_code, name_category FROM categories";
    $result_categories = mysqli_query($connection, $sql_query);
    if (!$result_categories) {
        print("Ошибка MySQL: " . mysqli_error($connection));
    } else {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    };
};

$main = include_template("main.php", [
    "categories" => $categories,
    "goods" => $goods
]);

$layout = include_template("layout.php", [
    "main" => $main,
    "categories" => $categories,
    "title" => "Главная",
    "is_auth" => $is_auth,
    "user_name" => $user_name

]);

print($layout);
