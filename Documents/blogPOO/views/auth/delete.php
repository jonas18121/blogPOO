<?php
session_start();
$pdo = Database::dbConnect();
(new PostTable($pdo))->deleteAllPostAdmin($_SESSION['auth']);
(new UserTable($pdo))->deleteUser($_SESSION['auth']);
session_destroy();
header('Location: ' . $router->url('login') . '?delete_user=1');
exit();