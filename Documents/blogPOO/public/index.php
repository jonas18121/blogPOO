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
    ->match('/admin/register', 'admin/authAdmin/register', 'register_admin')
    ->match('/admin/login', 'admin/authAdmin/login', 'login_admin')
    ->post('/admin/logout', 'admin/authAdmin/logout', 'logout_admin')
    ->post('/admin/delete', 'admin/authAdmin/delete', 'delete_admin')

        //USER//
    ->match('/user/register', 'user/authUser/register', 'register_user')
    ->match('/user/login', 'user/authUser/login', 'login_user')
    ->post('/user/logout', 'user/authUser/logout', 'logout_user')
    ->post('/user/delete', 'user/authUser/delete', 'delete_user')

        // COMMENT//
    ->match('/blog/[*:slug_post]/[i:id_post]/comment/[i:id]','comment/edit', 'comment_edit')
    ->get('/blog/[*:slug_post]/[i:id_post]/comment/[i:id]/delete','comment/delete', 'comment_delete')
    ->match('/blog/comment/new/[*:slug_post]/[i:id_post]','comment/new', 'comment_new')
    //->get('/blog/comments','comment/index', 'comments')
    
        //Blog//
    ->get('/blog/category/[*:slug]/[i:id]', 'exo/blog/category/categoryShow', 'category')
    ->get('/blog/article/[*:slug]/[i:id]', 'exo/blog/show', 'propositionCategory')
    ->match('/blog/[*:slug]/[i:id]', 'exo/blog/show', 'post')

        //PUBLIC//
    ->get('/contact','exo/contacte', 'contacte')
    ->get('/errors','404', '404')
    ->get('/','exo/home', 'home')
    ->run();