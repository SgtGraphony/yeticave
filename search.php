<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");

$categories = get_all_sql_cats($connection);

$page_main = include_template('/main-search.php',[
    'categories' => $categories
]);

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $user_query = filter_input(INPUT_GET, 'search', FILTER_DEFAULT) ?? null;
    $total_items = get_search_result_quantity($connection, $user_query);

    $current_page = $_GET['page'] ?? 1;
    $page_items = 9;
    $pages_count = ceil($total_items/$page_items);
    $offset = ($current_page - 1) * $page_items;
    $pages = range(1, $pages_count);
    $search_arr = get_search_result($connection, $user_query, $page_items, $offset);
        
$page_main = include_template('/main-search.php',[
        'categories' => $categories,
        'search_arr' => $search_arr,
        'current_page' => $current_page,
        'pages_count' => $pages_count,
        'pages' => $pages,
        'user_query' => $user_query
    ]);

    if(empty($user_query)){

        $search_arr = get_all_lots($connection);

        $page_main = include_template('/main-search.php',[
            'categories' => $categories,
            'search_arr' => $search_arr,
            'current_page' => $current_page,
            'pages_count' => $pages_count,
            'pages' => $pages
        ]);
    }
}
    
$layout_content = include_template('/layout.php', [
        'page_main' => $page_main,
        'title' => 'Поиск лота',
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth
    ]);  
    
print($layout_content);