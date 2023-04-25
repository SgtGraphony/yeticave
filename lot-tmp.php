<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");

$categories = get_all_sql_cats($connection);

$page_main = include_template('404.php');

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$sql_lot = get_lot_data($connection, $lot_id);
$query = mysqli_query($connection, $sql_lot);

if($query){

    $lot = mysqli_fetch_assoc($query);
    $min_bet = $lot['price'] + $lot['rate-step'];
}

if($lot){

    $page_main = include_template('main-lot.php', [
        'lot' => $lot,
        'categories' => $categories,
        'lot_id' => $lot_id,
        'min_bet' => $min_bet,
        'is_auth' => $is_auth
     ]);

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   $bet = filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_NUMBER_INT);

   if(empty($bet)){

    $error = "Ставка должна быть целым числом и больше нуля!";

   }

   if($bet < $min_bet){

    $error = "Ставка не может быть меньше $min_bet";

   }

   if(!empty($error)){

    $page_main = include_template('main-lot.php', [
        'lot' => $lot,
        'categories' => $categories,
        'error' => $error,
        'lot_id' => $lot_id,
        'min_bet' => $min_bet,
        'is_auth' => $is_auth,
        'bet' => $bet
     ]);
     
   } else {

    add_bet_db($connection, $user_id, $lot_id, $bet);
    header("Location: lot.php?id=$lot_id");
    exit;

   }

}};

$layout_content = include_template('layout.php', [
   'page_main' => $page_main,
   'title' => $lot['name'],
   'categories' => $categories,
   'user_name' => $user_name,
   'is_auth' => $is_auth,
   'lot_id' => $lot_id,
   'user_id' => $user_id
]);


print($layout_content);
print_r($_SERVER['REQUEST_METHOD'] . " "); 
print_r(mysqli_error($connection));