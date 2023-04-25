<?php

/**
 * Формирует SQL запрос на получение информации о лоте по его ID
 * @param $connection Ресурс соединения
 * @param int $lot_id Идентификатор лота
 */
function get_lot_data($connection, $lot_id){
    return "SELECT l.id, l.name, author_id, winner_id, rate_step, c.name AS category, price, image, expiration, description FROM lot l JOIN category c ON category_id = c.id WHERE l.id =$lot_id";
}

/**
 * Добавление ставки в базу данных
 * @param $connection Ресурс соединения
 * @param int $user_id Идентификатор пользователя
 * @param int $lot_id Идентификатор лота
 * @param int $price Сумма ставки
 */
function add_bet_db($connection, $user_id, $lot_id, $price){
    $sql = "INSERT INTO bet (user_id, lot_id, price) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $user_id, $lot_id, $price);
    $res = mysqli_stmt_execute($stmt);

    if(!$res){
        return mysqli_error($connection);
    }

    return $res;
}

/**
 * Получение SQL запроса на получение всех ставок от конкретного пользователя
 * @param $connection Ресурс соединения
 * @param int $user_id Идентификатор пользователя
 */
function get_user_bets($connection, $user_id){
    $bets_sql = "SELECT lot.id, category.name AS category, author_id, winner_id, user.contact, date_published, lot.name, DESCRIPTION, image, bet.price, lot.expiration, lot.rate_step FROM bet JOIN lot ON bet.lot_id = lot.id JOIN category ON category_id = category.id JOIN user ON author_id = user.id WHERE user_id = $user_id ORDER BY date_published DESC";
    $bets_query = mysqli_query($connection, $bets_sql);

        $bets = mysqli_fetch_all($bets_query, MYSQLI_ASSOC);
    
    return $bets;
}

/**
 * Получает все лоты из выбранной категории
 * @param $connection Ресурс соединения
 * @param string $selected_category Выбранная пользователем категория, полученная из строки запроса
 * @param int $limit Колличество элементов на странице
 * @param int $offset Смещение элементов по странице
 * @return array Массив лотов по выбранной категории
 */
function get_all_from_selected($connection, $selected_category, $limit, $offset){
 $sql = "SELECT lot.id, category.name AS category, category.cat_id, author_id, winner_id, start_date, lot.name, DESCRIPTION, image, price, expiration, rate_step FROM lot JOIN category ON lot.category_id = category.id WHERE category.cat_id = '$selected_category'
 ORDER by start_date DESC LIMIT $limit OFFSET $offset";
 $query = mysqli_query($connection, $sql);

 $res = mysqli_fetch_all($query, MYSQLI_ASSOC);

 return $res;
}

/**
 * Получает имя категории
 * @param $connection Ресурс соединения
 * @param string $selected_category Выбранная категория из стори запроса
 * @return 
 */
function get_category_name($connection, $selected_category){
    $sql = "SELECT category.name as category FROM category WHERE category.cat_id = '$selected_category'";
    $query = mysqli_query($connection, $sql);

    $res = mysqli_fetch_row($query);
    
    return $res;
}

/**
 * Получает колличество лотов по выбранной категории
 * @param $connection Ресурс соединения
 * @param string $selected_category Выбранная категория
 */
function get_count_from_selected($connection, $selected_category){
    $sql = "SELECT lot.id, category.name AS category, category.cat_id, author_id, winner_id, start_date, lot.name, DESCRIPTION, image, price, expiration, rate_step FROM lot JOIN category ON lot.category_id = category.id WHERE category.cat_id = '$selected_category'
    ORDER by start_date DESC";
    $query = mysqli_query($connection, $sql);
   
    $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
   
    return count($res);
   }

/**
 * Возвращает массив с историей ставок по конкретному лоту
 * @param $connection Ресурс соединения
 * @param int $lot_id Идентификатор лота
 * @return array $res
 *  */   
 function get_bet_history($connection, $lot_id){
    $sql = "SELECT user.name AS user_name, user.id AS user_id, bet.price, bet.date_published FROM bet JOIN user ON user_id = user.id WHERE lot_id = $lot_id ORDER BY bet.date_published DESC";
    $query = mysqli_query($connection, $sql);

    $res = mysqli_fetch_all($query, MYSQLI_ASSOC);

    return $res;
 }

 /**
  * Получаем все лоты без победителей
  * @param $connection Ресурс соединения
  * @return array Ассоциативный массив данных
  */
 function get_no_winner_lots($connection){
    $sql = "SELECT lot.id, cat_id AS category, author_id, start_date, lot.name, DESCRIPTION, image, price, expiration, rate_step FROM lot JOIN category ON category_id = category.id WHERE winner_id IS null";
    $query = mysqli_query($connection, $sql);

    $res = mysqli_fetch_all($query, MYSQLI_ASSOC);

    return $res;
 }

 /**
  * Добавление победителя ставок по лоту в базу данных
  * @param $connection Ресурс соединения
  * @param int $user_id Идентификатор пользователя
  * @param int $lot_id Идентификатор лота
  * @return 
  */
 function add_lot_winner($connection, $user_id, $lot_id){
    $sql = "UPDATE lot SET winner_id = $user_id WHERE lot.id = $lot_id";
    $res = mysqli_query($connection, $sql);

    return $res;
 }