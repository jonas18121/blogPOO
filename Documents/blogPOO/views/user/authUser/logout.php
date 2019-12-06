<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['admin'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}
session_destroy();
header('Location: ' . $router->url('login_user'));
exit();