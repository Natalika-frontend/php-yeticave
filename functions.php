<?php

// функция для добавления пробела после тысяч и знака рубля
function format_sum($sum)
{
    $sum = ceil($sum);
    if ($sum > 1000) {
        $sum = number_format($sum, 0, "", " ");
    }
    return $sum . " " . "₽";
}

// функция для форматирования оставшегося времени лота
function get_remaining_time($date)
{
//    устанавливаем часовой пояс
    date_default_timezone_set("Europe/Moscow");
//    получаем экземпляр переданной даты
    $end_date = date_create($date);
//    получаем экземпляр текущей даты
    $current_date = date_create();
//    находим разницу между датами
    $diff = date_diff($end_date, $current_date);
//    отформатируем полученную разницу
    $format_diff = date_interval_format($diff, "%d %H %I");
//    поместим в массив отформатированную строку
    $arr_diff = explode(" ", $format_diff);
//    переведем количество оставшихся дней в часы
    $hours = $arr_diff[0] * 24 + $arr_diff[1];
//    запишем в переменную количество оставшихся минут
    $minutes = $arr_diff[2];
//    отформатируем часы и минуты, чтоб впереди появлялся 0
    $hours = str_pad($hours, 2, 0, STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, 0, STR_PAD_LEFT);
//    поместим часы и минуты в массив
    $result[] = $hours;
    $result[] = $minutes;

    return $result;
}

;

// функция запроса для получения списка лотов
function get_list_lots()
{
    return "SELECT lots.id, lots.lot_name, lots.start_price, lots.lot_image, lots.date_finish, categories.name_category FROM lots INNER JOIN categories ON lots.category_id = categories.id ORDER BY lots.date_create DESC";
}

;

// функция получения информации о лоте
function get_lot($id)
{
    return "SELECT lots.lot_name, lots.lot_description, lots.lot_image, lots.date_finish, lots.start_price, categories.name_category FROM lots JOIN categories ON lots.category_id = categories.id WHERE lots.id = $id";
}

// валидация категории
function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
}

// валидация номера
function validate_number($num)
{
    if (!empty($num)) {
        $num = $num * 1;
        if (is_int($num) && $num > 0) {
            return NULL;
        }
        return "Содержимое поля должно быть целым числом больше нуля";
    }
}

// валидация даты
function validate_date($date)
{
    if (is_date_valid($date)) {
        $now = date_create("now");
        $d = date_create($date);
        $diff = date_diff($d, $now);
        $interval = date_interval_format($diff, "%d");

        if ($interval < 1) {
            return "Дата должна быть больше текущей не менее чем на один день";
        };
    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
}

;

// Создание нового лота
function get_query_create_lot($user_id)
{
    return "INSERT INTO my_yeticave.lots (lot_name, category_id, lot_description, start_price, bet_step, date_finish, lot_image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, $user_id)";
}