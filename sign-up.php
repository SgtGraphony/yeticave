<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");

if(get_all_sql_cats($connection)){

    $categories = get_all_sql_cats($connection);

$page_main = include_template('/main-sign-up.php', [
'categories' => $categories
]);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

$required_fields = ['email', 'password', 'name', 'contact'];

$errors[] = null;

$rules = [
    'email' => function($value) use ($connection){
        return validateEmail($value, $connection);
    },
    'password' => function($value){
        return validatePassword($value, 8);
    },
    'name' => function($value) use ($connection){
        return validateLength($value, 4, 20);
    },
    'contact' => function($value){
        return validateLength($value, 10, 50);
    }
];

$user = filter_input_array(INPUT_POST,[
    'email' => FILTER_DEFAULT,
    'password' =>FILTER_DEFAULT,
    'name' => FILTER_DEFAULT,
    'contact' => FILTER_DEFAULT
], true);


foreach($user as $key => $value){
    if(isset($rules[$key])){
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }

    if(in_array($key, $required_fields) && empty($value)){
        $errors[$key] = "Поле $key обязательно к заполнению";
    }
}

$user_data = get_user_data($connection);
$emails = array_column($user_data, 'email');
$names = array_column($user_data, 'name');

if(in_array($user['email'], $emails)){
    $errors['email'] = "Введенный e-mail уже используется!";
}
if(in_array($user['name'], $names)){
    $errors['name'] = "Введенное имя пользователя уже используется!";
}


$errors = array_filter($errors);

if(count($errors)){
    $page_main = include_template('/main-sign-up.php', [
        'errors' => $errors,
        'user' => $user,
        'categories' => $categories
    ]);
}

if(empty($errors)){

    $hash_password = hashPassword($user['password']);

    $res = sql_add_user($user, $connection, $hash_password);

    if($res){
        header("Location: login.php");
    }
    
}

}

$layout_content = include_template('/layout.php', [
    'page_main' => $page_main,
    'title' => 'Регистрация',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
 ]);

 print($layout_content);

 } else {
    header('HTTP/1.0 403 Forbidden');
 }