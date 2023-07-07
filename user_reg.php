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

$main_reg = include_template("main_reg.php", ["categories" => $categories]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ["email", "password", "name", "message"];
    $errors = [];

    $rules = [
        "email" => function ($value) {
            return validate_email($value);
        },
        "password" => function ($value) {
            return validate_length($value, 6, 8);
        },
        "message" => function ($value) {
            return validate_length($value, 12, 1000);
        }
    ];

    $user = filter_input_array(INPUT_POST, [
        "email" => FILTER_DEFAULT,
        "name" => FILTER_DEFAULT,
        "password" => FILTER_DEFAULT,
        "message" => FILTER_DEFAULT

    ], true);

    foreach ($user as $field => $value) {
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
        $main_reg = include_template("main_reg.php", [
            "categories" => $categories,
            "user" => $user,
            "errors" => $errors
        ]);
    } else {
        $users_data = get_users_data($connection);
        $emails = array_column($users_data, "email");
        $names = array_column($users_data, "user_name");
        if (in_array($user["email"], $emails)) {
            $errors["email"] = "Пользователь с таким e-mail уже зарегистрирован";
        }
        if (in_array($user["name"], $names)) {
            $errors["name"] = "Пользователь с таким именем уже зарегистрирован";
        }

        if (count($errors)) {
            $main_reg = include_template("main_reg.php", [
                "categories" => $categories,
                "user" => $user,
                "errors" => $errors
            ]);
        } else {
            $sql = "INSERT INTO my_yeticave.user (date_registration, email, user_name, user_password, user_contacts) VALUES (NOW(), ?, ?, ?, ?)";
            $user["password"] = password_hash($user["password"], PASSWORD_DEFAULT);

            $stmt = db_get_prepare_stmt_version($connection, $sql, $user);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $user_id = mysqli_insert_id($connection);
                header("Location: /login.php");
            } else {
                $error = mysqli_error($connection);
                var_dump($error);
            }
        }
    }
}

if (isset($_SESSION['id'])) {
    $main = http_response_code(403);
    exit();
}

$layout = include_template("layout.php", [
    'title' => "Регистрация",
    'main' => $main_reg,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout);
