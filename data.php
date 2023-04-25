<?php

session_start();

$is_auth = isset($_SESSION['username']);

$user_id = $_SESSION['id'] ?? null;

$user_name = $_SESSION['username'] ?? ''; // укажите здесь ваше имя