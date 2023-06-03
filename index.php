<?php

require_once("helpers.php");
require_once("functions.php");
//require_once("data.php");
require_once("init.php");

if (!$connect_db) {
    $error = mysqli_connect_error();
} else {
    $sql = "SELECT character_code, name FROM Categories";
    $result = mysqli_query($connect_db, $sql);
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect_db);
    }
}

$sql = get_query_list_lots();

$res = mysqli_query($connect_db, $sql);
if ($res) {
    $goods = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect_db);
}

$page_content = include_template("main.php", [
    "categories" => $categories,
    "goods" => $goods
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => $categories,
    "title" => "Главная"
]);

print($layout_content);