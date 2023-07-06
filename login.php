<?php

require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("connection.php");

if ($connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {

//    отправляем запрос для получения списка категорий
    $sql_query = "SELECT symbol_code, name_category FROM my_yeticave.categories";
    $result_categories = mysqli_query($connection, $sql_query);
    if (!$result_categories) {
        print("Ошибка MySQL: " . mysqli_error($connection));
    } else {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    };
};

$main_login = include_template("main_login.php", ["categories" => $categories]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ["email", "password"];
    $errors = [];

    $rules = [
        "email" => function ($value) {
            return validate_email($value);
        },
        "password" => function ($value) {
            return validate_length($value, 6, 8);
        }
    ];

    $user_info = filter_input_array(INPUT_POST, [
        "email" => FILTER_DEFAULT,
        "password" => FILTER_DEFAULT
    ], true);

    foreach ($user_info as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = "Поле необходимо заполнить";
        }
    }
    $errors = array_filter($errors);

    if (count($errors)) {
        $main_login = include_template("main_login.php", [
            "categories" => $categories,
            "user_info" => $user_info,
            "errors" => $errors
        ]);
    } else {
        $users_data = get_login($connection, $user_info["email"]);
        if ($users_data) {
            if (password_verify($user_info["password"], $users_data["user_password"])) {
                $issession = session_start();
                $_SESSION['name'] = $users_data["user_name"];
                $_SESSION['id'] = $users_data["id"];

                header("Location: /index.php");
            } else {
                $errors["password"] = "Вы ввели неверный пароль";
            }
        } else {
            $errors["email"] = "Пользователь с таким e-mail не зарегистрирован";
        }

        if (count($errors)) {
            $main_login = include_template("main_login.php", [
                "categories" => $categories,
                "user_info" => $user_info,
                "errors" => $errors
            ]);
        }
    }
}

$layout_login = include_template("layout.php", [
    'title' => "Вход",
    'main' => $main_login,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_login);
