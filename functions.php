<?php

function edit_price($price)
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, "", " ");
    }

    return $price . " " . "₽";
}

;

function get_time_left($date)
{
    date_default_timezone_set('Europe/Moscow'); // устанавливаем текущую таймзону
    $current_time = date_create(); // экземпляр даты, текущая дата
    $time_end = date_create($date); // экземпляр даты, переданный в функцию, дата окончания лота

    $diff = date_diff($time_end, $current_time); // получаем разницу между датами
    $time_count = date_interval_format($diff, "%d %H %I"); // получение экземпляра разницы в виде строки
    $arr_count = explode(" ", $time_count); // представление предыдущей строки в виде массива

    $hours = $arr_count[0] * 24 + $arr_count[1]; // преобразуем количество дней в часы, получаем количество оставшихся часов
    $min = intval($arr_count[2]); // получаем количество оставшихся минут

    $hours = str_pad($hours, 2, 0, STR_PAD_LEFT); // форматируем представление до двузначного вместо однозначного
    $min = str_pad($min, 2, 0, STR_PAD_LEFT);

    $result[] = $hours;
    $result[] = $min;

    return $result;
}

;

function get_query_list_lots()
{
    return "SELECT Lots.title, Lots.starting_price, Lots.image, Lots.date_end, Categories.name FROM Lots JOIN Categories ON Lots.category_id=categories.id WHERE Lots.date_end < NOW() ORDER BY date_creation DESC ";
}