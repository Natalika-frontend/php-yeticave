<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");
require_once("models.php");

if (!$connection) {
    $error = mysqli_connect_error();
} else {
    $sql = 'SELECT id, name_category FROM my_yeticave.categories';
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connection);
    }
}

$search = htmlspecialchars($_GET["search"]);


if ($search) {
    $items_count = get_count_lots($connection, $search);
    $cur_page = $_GET["page"] ?? 1;
    $page_items = 9;
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $goods = get_found_lots($connection, $search, $page_items, $offset);

    $pagination = "";

    if ($pages_count > 1) {
        echo $pagination;
    }
}

$header = include_template("header.php", [
    "categories" => $categories
]);

$main = include_template("main_search.php", [
    "categories" => $categories,
    "search" => $search,
    "goods" => $goods,
    "header" => $header,
    "pagination" => $pagination,
    "pages_count" => $pages_count,
    "pages" => $pages,
    "cur_page" => $cur_page
]);

$layout = include_template("layout.php", [
    "main" => $main,
    "categories" => $categories,
    "title" => "Результат поиска",
    "search" => $search,
    "is_auth" => $is_auth,
    "user_name" => $user_name
]);

print($layout);