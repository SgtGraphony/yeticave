<?php

/* Устанавливаем соединение с базой данных  */

$connection = mysqli_connect("localhost", "root", "", "yeticave");
    mysqli_set_charset($connection, "utf8");