<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");

$main_reg = include_template("main_reg.php", ["categories" => $categories]);

print($main_reg);
