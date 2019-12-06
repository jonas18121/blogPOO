<?php
$pageTitle          = "Suppression d'article";
$titleH1            = "Supprimer l'article : {$params['id']}";
$pageDescription    = "Ici c'est la page de suppression d'article";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['user'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}

//Auth::check();
if(!(new AdminModel())->isAuthenticatedAdmin()) header('Location: ' . $router->url('login_user') . '?security=1');

$id        = (int)$params['id'];
$pdo       = Database::dbConnect();
$admin     = (new AdminTable())->findOne((int)$_SESSION['admin']);
$postTable = new PostTable($pdo);
$postAdmin = $postTable->findPostAdmin($id, $admin->getId());

if(isset($postAdmin) && !is_string($postAdmin))
{
    if($postAdmin->getId() === $id)
    {
        (new CommentTable($pdo))->deleteAllCommentOfPost($id);
        $postTable->delete($id);
        header('Location: '. $router->url('admin_posts') . '?delete=1');
        exit();
    }
}else{
    header('Location: '. $router->url('admin_posts') . '?paramFalse=1');
    exit();
}