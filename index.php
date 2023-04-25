<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("winner.php");


/* Если соединение успешно - пишем запрос на получение карточек шести новых лотов и проверяем запрос */
if($connection){
$sql_lots = 'SELECT l.id, l.name, c.name AS category, price, image, expiration FROM lot l JOIN category c ON category_id = c.id ORDER BY start_date DESC LIMIT 6';
$result_lots = mysqli_query($connection, $sql_lots);
 
/* Запрос выполнен успешно */

   if ($result_lots){

    /* Получаем лоты в виде массива */

     $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

          } else {

           /* Выводим последнюю ошибку в случае неправильного запроса  */

            $error = mysqli_error($connection);
              print($error_layout);
}
;

/* Получаем список категорий */

if(get_all_sql_cats($connection)){

    $categories = get_all_sql_cats($connection);

} else {

        /* Выводим последнюю ошибку в случае неправильного запроса  */

        $error = mysqli_error($connection);
        print($error_layout);
}
;

$page_main = include_template('/main.php', [
    'lots' => $lots,
    'categories' => $categories
]);

$layout_content = include_template('/layout.php', [
    'page_main' => $page_main,
    'title' => 'Главная',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);

} else {
    print_error_msg();
}