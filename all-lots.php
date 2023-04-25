<?php
require_once("data.php");
require_once("functions.php");
require_once("init.php");
require_once("helpers.php");
require_once("models.php");

 $current_page = $_GET['page'] ?? 1;
 $page_items = 9;
 $offset = ($current_page - 1) * $page_items;

 $categories = get_all_sql_cats($connection);
 $selected_category = $_GET['category'];
 $lots = get_all_from_selected($connection, $selected_category, $page_items, $offset);
 $category_name = get_category_name($connection, $selected_category);

 $total_items = get_count_from_selected($connection, $selected_category);
 $pages_count = ceil($total_items / $page_items);
 $pages = range(1, $pages_count);

$page_main = include_template('/main-all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'category_name' => $category_name,
    'pages' => $pages,
    'selected_category' => $selected_category,
    'pages_count' => $pages_count,
    'current_page' => $current_page
]);

$layout_content = include_template('/layout.php', [
    'is_auth' => $is_auth,
    'categories' => $categories,
    'title' => 'Все лоты',
    'user_name' => $user_name,
    'page_main' => $page_main
]);

print($layout_content);