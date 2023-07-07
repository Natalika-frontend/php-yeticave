<?php

/**
 * Формирует SQL-запрос для получения списка новых лотов от определенной даты, с сортировкой
 * @return string SQL-запрос
 */
function get_list_lots()
{
    return "SELECT lots.id, lots.lot_name, lots.start_price, lots.lot_image, lots.date_finish, categories.name_category FROM lots INNER JOIN categories ON lots.category_id = categories.id ORDER BY lots.date_create DESC";
}

;

/**
 * Формирует SQL-запрос для показа лота на странице lot.php
 * @param integer $id id лота
 * @return string SQL-запрос
 */
function get_lot($id)
{
    return "SELECT lots.lot_name, lots.lot_description, lots.lot_image, lots.date_finish, lots.start_price, categories.name_category FROM lots JOIN categories ON lots.category_id = categories.id WHERE lots.id = $id";
}

/**
 * Формирует SQL-запрос для создания нового лота
 * @param integer $user_id id пользователя
 * @return string SQL-запрос
 */
function get_query_create_lot($user_id)
{
    return "INSERT INTO my_yeticave.lots (lot_name, category_id, lot_description, start_price, bet_step, date_finish, lot_image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, $user_id)";
}

;


/**
 * Возвращает массив данных пользователей: адрес электронной почты и имя
 * @param $con Подключение к MySQL
 * @return [Array | String] $users_data Двумерный массив с именами и емейлами пользователей
 * или описание последней ошибки подключения
 */
function get_users_data($con)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT email, user_name FROM my_yeticave.user";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $users_data = get_arrow($result);
            return $users_data;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Возвращает массив данных пользователя: id адресс электронной почты имя и хеш пароля
 * @param $con Подключение к MySQL
 * @param $email введенный адрес электронной почты
 * @return [Array | String] $users_data Массив с данными пользователя: id адресс электронной почты имя и хеш пароля
 * или описание последней ошибки подключения
 */
function get_login($con, $email)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT id, email, user_name, user_password FROM my_yeticave.user WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $users_data = get_arrow($result);
            return $users_data;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

;

/** Возвращает количество лотов соответствующих поисковым словам
 * @param $link mysqli Ресурс соединения
 * @param string $words Ключевые слова, введенные пользователем в форму поиска
 * @return [int | String] $count Количество лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_count_lots($link, $words)
{
    $sql = "SELECT COUNT(*) as cnt FROM my_yeticave.lots WHERE MATCH(lot_name, lot_description) AGAINST(?);";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $count = mysqli_fetch_assoc($res)["cnt"];
        return $count;
    }
    $error = mysqli_error($link);
    return $error;
}

/**
 * Возвращает массив лотов, соответствующих поисковым словам
 * @param $link mysqli Ресурс соединения
 * @param string $words ключевые слова, введенные пользователем в форму поиска
 * @return [Array | String] $goods Двумерный массив лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_found_lots($link, $words, $limit, $offset)
{
    $sql = "SELECT lots.id, lots.lot_name, lots.start_price, lots.lot_image,lots.date_finish, categories.name_category FROM my_yeticave.lots JOIN my_yeticave.categories ON lots.category_id=categories.id WHERE MATCH(lot_name, lot_description) AGAINST(?) ORDER BY date_create DESC LIMIT $limit OFFSET $offset;";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $goods = get_arrow($res);
        return $goods;
    }
    $error = mysqli_error($link);
    return $error;
}

{
}
