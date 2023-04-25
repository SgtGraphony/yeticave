<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");


if($_SESSION['id']){
$categories = get_all_sql_cats($connection);

$bets = get_user_bets($connection, $user_id);


$page_main = include_template('main-my-bets.php', [
  'categories' => $categories,
  'bets' => $bets,
  'user_id' => $user_id
]);

$layout_content = include_template('layout.php', [
    'page_main' => $page_main,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_id' => $user_id,
    'title' => 'Мои ставки'
]);

print($layout_content);

} else {
  
  header("Location: login.php");
    
}