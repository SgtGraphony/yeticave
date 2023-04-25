<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");

if(@$_SESSION['username']){

if(get_all_sql_cats($connection)){

    $categories = get_all_sql_cats($connection);

}

$page_main = include_template("main-add.php", [
    'categories' => $categories
]);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $categories_quantity = array_column($categories, 'id');

    $required_fields = [
    'lot-name', 'description', 'category', 'price', 'expiration', 'rate-step'
];

$errors = [];

$rules =[
    'lot-name' => function($value){
        return validateLength($value, 10, 128);
    },
    'description' => function($value){
        return validateLength($value, 10, 500);
    },
    'category' => function($value) use ($categories_quantity){
        return validateCategory($value, $categories_quantity);
    },
    'price' => function($value){
        return validateNumber($value);
    },
    'expiration' => function($value){
        return validateDate($value);
    },
    'rate-step' => function($value){
        return validateNumber($value);
    }

];

$lot = filter_input_array(INPUT_POST,
['lot-name' => FILTER_DEFAULT,
 'description' => FILTER_DEFAULT,
 'category' => FILTER_DEFAULT,
 'price' => FILTER_DEFAULT,
 'expiration' => FILTER_DEFAULT,
 'rate-step' => FILTER_DEFAULT],
      true);

foreach ($lot as $key => $value){
    if(isset($rules[$key])){
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }

    if(in_array($key, $required_fields) && empty($value)){
        $errors[$key] = "Поле $key обязательно к заполнению";
    }
}

$errors = array_filter($errors);

if(!empty($_FILES['image']['name'])) {
    
    $tmp_name = $_FILES['image']['tmp_name'];
    $path = $_FILES['image']['name'];
    $filename = null;

    $mime_type = mime_content_type($tmp_name);
    
    if($mime_type = "image/png"){

        $filename = uniqid() . '.png';
        $lot['image'] = $filename;
        move_uploaded_file($tmp_name, 'uploads/' . $filename);

    } elseif($mime_type = "image/jpg"){

        $filename = uniqid() . '.jpg';
        $lot['image'] = $filename;
        move_uploaded_file($tmp_name, 'uploads/' . $filename);
    } else {
        $errors['image'] = "Файл должен быть в формате .png/.jpg";
    } 
} else {
    $errors['image'] = "Вы не загрузили изображение!";
}

    if(count($errors)){
        $page_main = include_template('/main-add.php',[
            'categories' => $categories,
            'errors' => $errors,
            'lot' => $lot
        ]);
    } 

    if(empty($errors)){

        $page_main = include_template('/main-add.php',[
            'categories' => $categories
         ]);

         if(add_sql_lot($connection, @$lot, @$user_id)){

            $lot_id = mysqli_insert_id($connection);
    
            header("Location: /lot.php?id=" . $lot_id);
            
        } else {
            
            print(mysqli_error($connection));
        }
    }
}


     $layout_content = include_template('/layout.php', [
        'page_main' => $page_main,
        'title' => 'Добавление лота',
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth
     ]);
    
     print($layout_content);
     
    } else {
        header("Location: login.php");
    }