<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['admin'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}
$pdo = Database::dbConnect();
(new CommentTable($pdo))->deleteAllCommentUser($_SESSION['user']);
(new UserTable($pdo))->delete($_SESSION['user']);
session_destroy();
header('Location: ' . $router->url('login_user') . '?delete_user=1');
exit();