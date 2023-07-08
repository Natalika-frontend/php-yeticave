<?php


require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");
require_once("models.php");

if (!$connection) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    $sql_query = "SELECT symbol_code, name_category FROM my_yeticave.categories";
    $result_categories = mysqli_query($connection, $sql_query);
    if (!$result_categories) {
        print("Ошибка MySQL: " . mysqli_error($connection));
    } else {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    };
};


if ($is_auth) {
    $bets = get_bets($connection, $_SESSION["id"]);
}

$header = include_template("header.php", [
    "categories" => $categories
]);


$main = include_template("main-my-bets.php", [
    "categories" => $categories,
    "header" => $header,
    "bets" => $bets,
    "is_auth" => $is_auth
]);

$layout = include_template("layout.php", [
    "main" => $main,
    "categories" => $categories,
    "title" => "Мои ставки",
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

print($layout);
