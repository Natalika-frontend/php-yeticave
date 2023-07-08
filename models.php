<?php

/**
 * Формирует SQL-запрос для получения списка новых лотов от определенной даты, с сортировкой
 * @return string SQL-запрос
 */
function get_list_lots()
{
    return "SELECT my_yeticave.lots.id, my_yeticave.lots.lot_name, my_yeticave.lots.start_price, my_yeticave.lots.lot_image, my_yeticave.lots.date_finish, my_yeticave.categories.name_category FROM my_yeticave.lots INNER JOIN my_yeticave.categories ON lots.category_id = categories.id ORDER BY lots.date_create DESC";
}

;

/**
 * Формирует SQL-запрос для показа лота на странице lot.php
 * @param integer $id id лота
 * @return string SQL-запрос
 */
function get_lot($id)
{
    return "SELECT my_yeticave.lots.lot_name, my_yeticave.lots.lot_description, my_yeticave.lots.lot_image, my_yeticave.lots.date_finish, my_yeticave.lots.start_price, my_yeticave.lots.bet_step, my_yeticave.lots.user_id, my_yeticave.categories.name_category FROM my_yeticave.lots JOIN my_yeticave.categories ON lots.category_id = categories.id WHERE my_yeticave.lots.id = $id";
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

/**
 * Возвращает массив из десяти последних ставок на этот лот
 * @param $con Подключение к MySQL
 * @param int $id_lot Id лота
 * @return [Array | String] $list_bets Ассоциативный массив со списком ставок на этот лот из базы данных
 * или описание последней ошибки подключения
 */
function get_bets_history($con, $id_lot)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT my_yeticave.user.user_name, bets.sum_bet, DATE_FORMAT(bet_date, '%d.%m.%y %H:%i') AS bet_date
        FROM my_yeticave.bets
        JOIN my_yeticave.lots ON bets.lot_id=lots.id
        JOIN my_yeticave.user ON bets.user_id=user.id
        WHERE lots.id=$id_lot
        ORDER BY bets.bet_date DESC LIMIT 10;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Записывает в базу данных сделанную ставку
 * @param $con mysqli Ресурс соединения
 * @param int $sum Сумма ставки
 * @param int $user_id ID пользователя
 * @param int $lot_id ID лота
 * @return bool $res Возвращает true в случае успешной записи
 */
function add_bet_db($con, $sum, $user_id, $lot_id)
{
    $sql = "INSERT INTO my_yeticave.bets (bet_date, sum_bet, user_id, lot_id) VALUE (NOW(), ?, $user_id, $lot_id);";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $sum);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        return $res;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает массив ставок пользователя
 * @param $con Подключение к MySQL
 * @param int $id Id пользователя
 * @return [Array | String] $list_bets Ассоциативный массив ставок
 *  пользователя из базы данных
 * или описание последней ошибки подключения
 */
function get_bets($con, $id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        return $error;
    } else {
        $sql = "SELECT DATE_FORMAT(my_yeticave.bets.bet_date, '%d.%m.%y %H:%i') AS bet_date, my_yeticave.bets.sum_bet, my_yeticave.lots.lot_name, my_yeticave.lots.lot_description, my_yeticave.lots.lot_image, my_yeticave.lots.date_finish, my_yeticave.lots.id, my_yeticave.categories.name_category
        FROM my_yeticave.bets
        JOIN my_yeticave.lots ON bets.lot_id=lots.id
        JOIN my_yeticave.user ON bets.user_id=user.id
        JOIN my_yeticave.categories ON lots.category_id=categories.id
        WHERE bets.user_id=$id
        ORDER BY bets.bet_date DESC;";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $list_bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $list_bets;
        }
        $error = mysqli_error($con);
        return $error;
    }
}
