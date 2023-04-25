<?php


function price_format($price) {
    ceil($price);

    if($price < 1000) {
      $price ;
    } else {
      $price = number_format($price, 0, ' ', ' ');
    }

  return $price . ' ₽';
  };


// $date - дата в формате ГГГГ-ММ-ДД

 function get_time_left ($date) {
  date_default_timezone_set('Europe/Moscow');
  $final_date = date_create($date);
  $cur_date = date_create("now");
  $diff = date_diff($final_date, $cur_date);
  $format_diff = date_interval_format($diff, "%d %H %I");
  $arr = explode(" ", $format_diff);

  $hours = $arr[0] * 24 + $arr[1];
  $minutes = intval($arr[2]);
  $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
  $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

  $res[] = $hours;
  $res[] = $minutes;

  return $res;
}



function print_error_msg() {

    $error = mysqli_connect_error();

    $err_msg = include_template('/error.php', [
        'error' => $error
    ]);
    
    $error_layout = include_template('/layout.php', [
        'page_main' => $err_msg
    ]);

    return $error_layout;
}


/**
 * Получение списка категорий из Базы Данных
 * @param $connection Ресурс соединения
 * @return $categories Массив со всеми категориями
 */
function get_all_sql_cats($connection){
    if($connection){

    $sql_categories = 'SELECT id, cat_id, name FROM category';
    $result_categories = mysqli_query($connection, $sql_categories);

    $categories= mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

    return $categories;

} else {

    print(print_error_msg());
}

}

/**
 * Добавление лота в БД на основе данных, получаемых от пользователя
 * @param $connection Ресурс соединения
 * @param array $lot Массив с данными, полученный через форму
 * @param int $user_id Идентификатор пользователя
 */
function add_sql_lot($connection, $lot, $user_id){
$sql_add =
        'INSERT INTO lot (category_id, author_id, NAME, DESCRIPTION, image, price, expiration, rate_step) 
         VALUES 
         (?, ?, ?, ?, ?, ?, ?, ?)';
         
        $stmt = mysqli_prepare($connection, $sql_add);
        mysqli_stmt_bind_param($stmt, 'ssssssss', $lot['category'], $user_id, $lot['lot-name'], $lot['description'], $lot['image'], $lot['price'], $lot['expiration'], $lot['rate-step']);
        $execution = mysqli_stmt_execute($stmt);

        return $execution;
}

/**
 * Проверяет существует ли указанная категория $cat_id в списке $categories_list
 * @param $cat_id Проверяемое значение
 * @param array $categories_list Массив, в котором происходит проверка
 */
function validateCategory($cat_id, $categories_list){
  if(!in_array($cat_id, $categories_list)){
    return "Указана несуществующая категория";
  }
  return null;
}

/**
 * Проверка значения на соответствие определенной длинне
 * @param int $min Минимальное значение
 * @param int $max Максимальное значение
 * @param int $value Принимаемое значение
 */
function validateLength($value, $min, $max){

    if($value){
        $length = strlen($value);
        if($length < $min or $length > $max){
            return "Значение должно быть в диапазоне от $min до $max символов";
        }
    }
    return null;
}

/**
 * Проверяет что значение целое и больше нуля
 * @param int $value Принимаемое значение
 */
function validateNumber($value){
    if(!empty($value)){
        $value *= 1;
        if(is_int($value) && $value > 0){
            return null;
        }
        return "Содержимое поля должно быть целое и больше нуля";
    }
}

/**
 * Проверяет дату на соответствие формату ГГГГ-ММ-ДД
 * @param string $date Принимаемое значение
 */
function validateDate($date){
    if(is_date_valid($date)){
        $now = date_create("now");
        $dd = date_create($date);
        $diff = date_diff($dd, $now);
        $interval = date_interval_format($diff, "%d");
        if($interval < 1){
            return "Дата должна быть больше текущей минимум на один день";
        }
    } else {
        return "Содержимое поля «дата завершения» должно быть датой в формате «ГГГГ-ММ-ДД»";
    }
}
/**
 * @param $key Ключ
 * @param $index Значение
 * Проверяет установлено ли для поля $key значение $index
 */
function add_class_name($key, $index){
    $classname = "";
    if(isset($key["$index"])){
        $classname = "form__item--invalid";
    }
    return $classname;
}

/**
 * Проверка электронный почты на соответствие формату e-Mail
 * @param string $value Принимаемое значение
 */
function validateEmail($value){
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == FALSE){
            return "Email должен быть в формате example@mail.com";
        }
}

/**
 * Проверка пароля на соответствие требованиям
 * @param string $value Принимаемое значение
 * @param int $min Минимально допустимая длинна
 */
function validatePassword($value, $min){
    if($value){
        $length = strlen($value);
        if($length < $min){
            return "Пароль должен быть не менее $min символов";
        }
    }
    return null;
}

/**
 * Хеширует принятое значение пароля
 * @param string $value Принимаемое значение
 * @return $password_hash Хешированный пароль
 */
function hashPassword($value){
    $password_hash = password_hash($value, PASSWORD_DEFAULT);

    return $password_hash;
}

/**
 * Добавление пользователя в базу данных
 * @param array $user Регистрационные данные, полученные из формы
 * @param $connection Ресурс соединения 
 * @param $hash_password Хешированный пароль
 * @return $res Результат
 */
function sql_add_user($user, $connection, $hash_password){
    $sql = 'INSERT into user (email, password, name, contact) values (?, ?, ?, ?)';
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $user['email'], $hash_password, $user['name'], $user['contact']);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}
/**
 * Получаем массив с именами, почтой и паролями пользователей
 * @param $connection Ресурс соединения
 * @return $user_data Массив с информацией
 */
function get_user_data($connection){
    $sql = 'SELECT name, email, password FROM user';
    $query = mysqli_query($connection, $sql);
    $row = mysqli_num_rows($query);

    if($row === 1){
        $user_data = mysqli_fetch_assoc($query);
    } else if($row > 1){
        $user_data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    return $user_data;
}

/**
* Получает массив данных о лотах на основе запроса от пользователя через поисковую строку
* @param $connection mysqli Ресурс соединения
* @param string $user_query Поисковой запрос
* @param int $page_items Колличество предметов на странице
* @param int $offset Параметр смещения
* @return [int | String] $result Массив
*/
function get_search_result($connection, $user_query, $page_items, $offset){

    $search_mysqli = "SELECT lot.id, category.name AS category, lot.author_id, lot.winner_id, lot.start_date, lot.name, lot.description, lot.image, lot.price, lot.expiration, lot.rate_step 
    FROM lot 
    JOIN category 
    ON lot.category_id = category.id 
    WHERE MATCH(lot.NAME, lot.DESCRIPTION) AGAINST (?) 
    ORDER BY lot.start_date 
    DESC LIMIT $page_items OFFSET $offset";

    $stmt = mysqli_prepare($connection, $search_mysqli);
    mysqli_stmt_bind_param($stmt, 's', $user_query);
    mysqli_stmt_execute($stmt);
    $search_result = mysqli_stmt_get_result($stmt);

        $result = mysqli_fetch_all($search_result, MYSQLI_ASSOC);
    
    return $result;
}

/**
 * Получает массив данных о всех лотах из базы данных
 * @param $connection Ресурс соединения
 * @return array Массив со всеми лотами
 */
function get_all_lots($connection){

    $sql = "SELECT lot.id, category.name AS category, lot.author_id, lot.winner_id, lot.start_date, lot.name, lot.description, lot.image, lot.price, lot.expiration, lot.rate_step 
    FROM lot 
    JOIN category 
    ON lot.category_id = category.id";
    $query = mysqli_query($connection, $sql);
    $lots = mysqli_fetch_all($query, MYSQLI_ASSOC);

    return $lots;
}

/**
* Получает итоговое колличество на поисковой запрос
* @param $connection mysqli Ресурс соединения
* @param string $user_query Поисковой запрос
* @return [int | String] $result Общее колличество
*/
function get_search_result_quantity($connection, $user_query){

    $sql = "SELECT COUNT(*) as total FROM lot WHERE MATCH(NAME, DESCRIPTION) AGAINST (?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 's', $user_query);
    mysqli_stmt_execute($stmt);
    $search_result = mysqli_stmt_get_result($stmt);

    if($search_result){

        $result = mysqli_fetch_assoc($search_result)['total'];
        return $result;

    } else {
        
        $error = mysqli_error($connection);
        return $error;
    }
}
