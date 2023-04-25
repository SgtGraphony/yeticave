<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");

$lots = get_all_lots($connection);

foreach($lots as $lot){
    if(strtotime("now") >= strtotime($lot['expiration'])){
        $lot_id = $lot['id'] ?? null;
        $bets = get_bet_history($connection, $lot['id']);
        $user_id = $bets[0]['user_id'] ?? null;
        if($user_id !== null){
            add_lot_winner($connection, $user_id, $lot_id);
        }
        
        
    }
}

