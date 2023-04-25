<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");

if(get_all_sql_cats($connection)){

    $categories = get_all_sql_cats($connection);

}

$page_main = include_template('/main-login.php', [
    'categories' => $categories
]);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $required_fields = ['email', 'password'];
    $errors[] = null;

    $rules = [
        'email' => function($value){
            return validateEmail($value);
        },
        'password' => function($value){
            return validatePassword($value, 8);
        }
    ];

    $login_data = filter_input_array(INPUT_POST,[
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ], true);

    foreach($login_data as $key => $value){

        if(isset($rules[$key])){
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if((in_array($key, $required_fields)) && empty($value)){
            $errors[$key] = "Поле $key обязательно к заполнению!";
        }
           
    }

    $email = $login_data['email'];
    $user_data = get_user_data($connection);
    $emails = array_column($user_data, 'email');

    if(in_array($login_data['email'], $emails)){

        $data_sql = "SELECT email, password, name, id FROM user WHERE email = '$email'";
        $data_query = mysqli_query($connection, $data_sql);

        if($data_query){
    
            $data = mysqli_fetch_assoc($data_query);
            $password = array_column($data, 'password');

            if(!password_verify($login_data['password'], $data['password'])){
                $errors['password'] = "Вы ввели неверный пароль!";
            }
        }
    } 

    if(!in_array($login_data['email'], $emails) && !empty($login_data['email'])){

        $errors['email'] = "E-Mail не зарегистрирован";

    }

    $errors = array_filter($errors);

    if(count($errors)){
        $page_main = include_template('/main-login.php', [
            'categories' => $categories,
            'errors' => $errors,
            'login-data' => $login_data
        ]);   
    }

    if(empty($errors)){

        $issession = session_start();
        $_SESSION['username'] = $data['name'];
        $_SESSION['id'] = $data['id'];

        header("Location: index.php");
    }
}

$layout_content = include_template('/layout.php', [
    'page_main' => $page_main,
    'title' => 'Вход на сайт',
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
 ]);

 print($layout_content);