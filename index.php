<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");


$main = include_template("main.php", [
    "categories" => $categories,
    "goods" => $goods
]);

$layout = include_template("layout.php", [
    "main" => $main,
    "categories" => $categories,
    "title" => "Главная"
]);

print($layout);
