<?php
/**
 * * Форматирует цену лота - разделяет пробелом разряды числа, добавляет знак рубля
 * @param integer $sum Цена лота
 * @return string Как цена будет показываться в карточке
 */
function format_sum($sum)
{
    $sum = ceil($sum);
    if ($sum > 1000) {
        $sum = number_format($sum, 0, "", " ");
    }
    return $sum . " " . "₽";
}

/** Возвращает количество целых часов и остатка минут от настоящего времени до даты окончания приема ставок
 * @param string $date Дата истечения времени
 * @return array
 */
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

/**
 * Валидирует поле категории, если такой категории нет в списке
 * возвращает сообщение об этом
 * @param int $id категория, которую ввел пользователь в форму
 * @param array $allowed_list Список существующих категорий
 * @return string Текст сообщения об ошибке
 */
function validate_category($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
}

/**
 * Проверяет что содержимое поля является числом больше нуля
 * @param string $num число которое ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
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

/**
 * Проверяет что дата окончания торгов не меньше одного дня
 * @param string $date дата, которую ввел пользователь в форму
 * @return string Текст сообщения об ошибке
 */
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

/**
 * Проверяет что содержимое поля является корректным адресом электронной почты
 * @param string $email адрес электронной почты
 * @return string Текст сообщения об ошибке
 */
function validate_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail должен быть корректным";
    }
}

;

/**
 * Проверяет что содержимое поля укладывается в допустимый диапазон
 * @param string $value содержимое поля
 * @param int $min минимальное количество символов
 * @param int $max максимальное количество символов
 * @return string Текст сообщения об ошибке
 */
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
}

;

/** Возвращает массив из объекта результата запроса
 * @param object $result_query mysqli Результат запроса к базе данных
 * @return array
 */
function get_arrow($result_query)
{
    $row = mysqli_num_rows($result_query);
    if ($row === 1) {
        $arrow = mysqli_fetch_assoc($result_query);
    } else if ($row > 1) {
        $arrow = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
    }

    return $arrow;
}

