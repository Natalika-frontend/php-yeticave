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

$main_lot = include_template("main-404.php", [
    "categories" => $categories
]);

$layout = include_template("layout.php", [
    "main" => $main_lot,
    "categories" => $categories,
    "title" => "Страница не найдена",
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if ($id) {
    $sql_lot = get_lot($id);
} else {
    print($layout);
    die();
}

$result_lot = mysqli_query($connection, $sql_lot);
if ($result_lot) {
    $lot = get_arrow($result_lot);
} else {
    $error = mysqli_error($connection);
}

if (!$lot) {
    print($layout);
    die();
}

$history = get_bets_history($connection, $id);
$current_price = max($lot["start_price"], $history[0]["sum_bet"] ?? "");
$min_bet = $current_price + $lot["bet_step"];


$header = include_template("header.php", [
    "categories" => $categories
]);

$main_lot = include_template("main_lot.php", [
    "categories" => $categories,
    "header" => $header,
    "lot" => $lot,
    "is_auth" => $is_auth,
    "current_price" => $current_price,
    "min_bet" => $min_bet,
    "id" => $id,
    "history" => $history
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bet = filter_input(INPUT_POST, "cost", FILTER_VALIDATE_INT);

    if ($bet < $min_bet) {
        $error = "Ставка не может быть меньше $min_bet";
    }

    if (empty($bet)) {
        $error = "Ставка должна быть целым числом, больше нуля";
    }

    if ($error) {
        $main_lot = include_template("main_lot.php", [
            "categories" => $categories,
            "header" => $header,
            "lot" => $lot,
            "is_auth" => $is_auth,
            "current_price" => $current_price,
            "min_bet" => $min_bet,
            "error" => $error,
            "id" => $id,
            "history" => $history
        ]);
    } else {
        $result_lot = add_bet_db($connection, $bet, $_SESSION["id"], $id);
        header("Location: /lot.php?id=" . $id);
    }
}

$layout = include_template("layout.php", [
    "main" => $main_lot,
    "categories" => $categories,
    "title" => $lot["lot_name"],
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

print($layout);
