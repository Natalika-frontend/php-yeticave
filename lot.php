<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");

if (!$connection) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $sql_query = "SELECT symbol_code, name_category FROM categories";
    $result_categories = mysqli_query($connection, $sql_query);
    if (!$result_categories) {
        print("Ошибка MySQL: " . mysqli_error($connection));
    } else {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    };
};

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql_lot = get_lot($id);
} else {
    http_response_code(404);
    die();
}

$result_lot = mysqli_query($connection, $sql_lot);
if (!$result_lot) {
    $error = mysqli_error($connection);
} else {
    $lot = mysqli_fetch_assoc($result_lot);
}

if (!$lot) {
    http_response_code(404);
    die();
}

$main_lot = include_template("main_lot.php", [
    "categories" => $categories,
    "lot" => $lot
]);

$layout = include_template("layout.php", [
    "main" => $main_lot,
    "categories" => $categories,
    "title" => "Главная",
    "is_auth" => $is_auth,
    "user_name" => $user_name

]);

print($layout);
