<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");

$categories = get_all_sql_cats($connection);
$lot_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$lot_sql = get_lot_data($connection, $lot_id);
$lot_query = mysqli_query($connection, $lot_sql);
$lot = mysqli_fetch_assoc($lot_query);

$bets = get_bet_history($connection, $lot_id);
$bets_count = count($bets);

if(!empty($lot)){

 $current_price = $bets[0]['price'] ?? $lot['price'];
 $min_bet = $current_price + $lot['rate_step'];

 $page_main = include_template("main-lot.php", [
   'categories' => $categories,
   'lot' => $lot,
   'current_price' => $current_price,
   'min_bet' => $min_bet,
   'lot_id' => $lot_id,
   'is_auth' => $is_auth,
   'bets' => $bets,
   'bets_count' => $bets_count
 ]);

 if($_SERVER['REQUEST_METHOD'] == 'POST'){

   $bet = filter_input(INPUT_POST, "cost", FILTER_VALIDATE_INT);

   if(!is_int($bet)){
      $error = "Ставка должна быть целым числом и больше нуля!";
   }

   if($bet < $min_bet && $bet > 0) {
      $error = "Ставка не может быть меньше $min_bet";
   }

   if(!$error){
      add_bet_db($connection, $user_id, $lot_id, $bet);
      header("Location: /lot.php?id=" . $lot_id);
      exit;
   } else {

      $page_main = include_template("main-lot.php", [
         'categories' => $categories,
         'lot' => $lot,
         'current_price' => $current_price,
         'min_bet' => $min_bet,
         'bet' => $bet,
         'error' => $error,
         'lot_id' => $lot_id,
         'bets' => $bets,
         'bets_count' => $bets_count
       ]);
   }

 }

 } else {

   $page_main = include_template("404.php");

 }

$layout_content = include_template("layout.php", [
   'categories' => $categories,
   'page_main' => $page_main,
   'user_name' => $user_name,
   'user_id' => $user_id,
   'title' => $lot['name'],
   'is_auth' => $is_auth
]);

print($layout_content);