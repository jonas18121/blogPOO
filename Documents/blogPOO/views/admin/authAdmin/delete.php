<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if( isset($_SESSION['user'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}

$sessoin        = (int)$_SESSION['admin'];
$pdo            = Database::dbConnect();
$postTable      = new PostTable($pdo);
$commentTable   = new CommentTable($pdo);

$AllPostAdmins  = $postTable->findAllPostAdmin($sessoin);

if(isset($AllPostAdmins) && !empty($AllPostAdmins) && !is_string($AllPostAdmins))
{
    foreach ($AllPostAdmins as $AllPostAdmin)
    {
        $commentTable->deleteAllCommentOfPost($AllPostAdmin->getId());
    }
}

$postTable->deleteAllPostAdmin($sessoin);
$commentTable->deleteAllCommentAdmin($sessoin);
(new AdminTable($pdo))->delete($sessoin);
session_destroy();
header('Location: ' . $router->url('login_user') . '?delete_admin=1');
exit();