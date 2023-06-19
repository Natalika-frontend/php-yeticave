<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");

if (!$connection) {
    $error = mysqli_connect_error();
} else {
    $sql = 'SELECT `id`, `name_category` FROM categories';
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connection);
    }

    $categories_id = array_column($categories, "id");

    $content = include_template('add.php', ['categories' => $categories]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];
        $errors = [];

        $rules = [
            "category" => function ($value) use ($categories_id) {
                return validate_category($value, $categories_id);
            },
            "lot-rate" => function ($value) {
                return validate_number($value);
            },
            "lot-step" => function ($value) {
                return validate_number($value);
            },
            "lot-date" => function ($value) {
                return validate_date($value);
            }
        ];

        $lot = filter_input_array(INPUT_POST, [
            "lot-name" => FILTER_DEFAULT,
            "category" => FILTER_DEFAULT,
            "message" => FILTER_DEFAULT,
            "lot-rate" => FILTER_DEFAULT,
            "lot-step" => FILTER_DEFAULT,
            "lot-date" => FILTER_DEFAULT
        ], true);

        foreach ($lot as $field => $value) {
            if (isset($rules[$field])) {
                $rule = $rules[$field];
                $errors[$field] = $rule($value);
            }
            if (in_array($field, $required) && empty($value)) {
                $errors[$field] = "Поле $field необходимо заполнить";
            }
        }
        $errors = array_filter($errors);

        if (!empty($_FILES["lot_img"]["name"])) {
            $tmp_name = $_FILES["lot_img"]["tmp_name"];
            $path = $_FILES["lot_img"]["name"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type === "image/jpeg") {
                $ext = ".jpg";
            } else {
                if ($file_type === "image/png") {
                    $ext = ".png";
                }
            };
            if ($ext) {
                $filename = uniqid() . $ext;
                $lot["path"] = "uploads/" . $filename;
                move_uploaded_file($_FILES["lot_img"]["tmp_name"], "uploads/" . $filename);
            } else {
                $errors["lot_img"] = "Допустимые форматы файлов: jpg, jpeg, png";
            }
        } else {
            $errors["lot_img"] = "Вы не загрузили изображение";
        }
        if (count($errors)) {
            $main_add_lot = include_template("main_add_lot.php", [
                "categories" => $categories,
                "lot" => $lot,
                "errors" => $errors
            ]);
        } else {
            $sql = get_query_create_lot(2);
            $stmt = db_get_prepare_stmt_version($connection, $sql, $lot);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $lot_id = mysqli_insert_id($connection);
                header("Location: /lot.php?id=" . $lot_id);
            } else {
                $error = mysqli_error($connection);
            }
        }
    }

    $main_add_lot = include_template("main_add_lot.php", [
        "categories" => $categories,
        "lot" => $lot
    ]);

    $page_head = include_template("head.php", [
        "title" => "Добавить лот"
    ]);

    $layout = include_template("layout_add_lot.php", [
        "main" => $main_add_lot,
        "categories" => $categories,
        "is_auth" => $is_auth,
        "user_name" => $user_name
    ]);
}
print($page_head);
print($layout);