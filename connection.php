<?php

// устанавливаем соединение с базой данных
$connection = mysqli_connect("localhost", "root", "", "my_yeticave");

// устанавливаем кодировку
mysqli_set_charset($connection, "utf8");