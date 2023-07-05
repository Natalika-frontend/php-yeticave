<?php

// устанавливаем соединение с базой данных
$connection = mysqli_connect("127.0.0.1", "root", "", "my_yeticave");

// устанавливаем кодировку
mysqli_set_charset($connection, "utf8");