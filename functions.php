<?php

$format_sum = function ($sum) {
    $sum = ceil($sum);
    if ($sum > 1000) {
        $sum = number_format($sum, 0, "", " ");
    }
    return $sum . " " . "₽";
};