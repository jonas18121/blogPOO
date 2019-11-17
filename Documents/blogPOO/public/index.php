<?php
require 'autoload.php';
spl_autoload_register('autoload');

/* on va efficher le temps que met le fichier a charger */
//define('DEBUG_TIME', microtime(true));

//réécrire l'url sans le paramètre ?page=1
if(isset($_GET['page']) && $_GET['page'] === '1'){
    $uri = explode('?', $_SERVER["REQUEST_URI"])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)){
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}
$router = new Router(dirname(__DIR__) . '/views');

// 1 route dans url, 2 chemin du fichier php = views/exo/home.php, 3 nom de la page
$router
        //Blog//
    ->get('/blog/category/[*:slug]/[i:id]', 'exo/blog/category/categoryShow', 'category')
    ->get('/blog/article/[*:slug]/[i:id]', 'exo/blog/show', 'propositionCategory')
    ->get('/blog/[*:slug]/[i:id]', 'exo/blog/show', 'post')
        //ADMIN - POST//
    ->match('/admin/post/new','admin/post/new', 'admin_post_new')
    ->match('/admin/post/[i:id]','admin/post/edit', 'admin_post')
    ->post('/admin/post/[i:id]/delete','admin/post/delete', 'admin_post_delete')
    ->get('/admin','admin/post/index', 'admin_posts')
        //ADMIN - CATEGORY//
    ->match('/admin/category/new','admin/category/new', 'admin_category_new')
    ->match('/admin/category/[i:id]','admin/category/edit', 'admin_category')
    ->post('/admin/category/[i:id]/delete','admin/category/delete', 'admin_category_delete')
    ->get('/admin/categories','admin/category/index', 'admin_categories')
        //ADMIN//
    ->match('/register', 'auth/register', 'register')
    ->match('/login', 'auth/login', 'login')
    ->post('/logout', 'auth/logout', 'logout')
    ->post('/delete_user', 'auth/delete', 'delete_user')
        //PUBLIC//
    ->get('/contact','exo/contacte', 'contacte')
    ->get('/typages','exo/typage', 'typage')
    ->get('/errors','404', '404')
    ->get('/','exo/home', 'home')
    ->run();