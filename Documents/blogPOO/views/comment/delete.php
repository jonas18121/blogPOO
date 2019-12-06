<?php
$pageTitle          = "Suppression de commentaire";
$titleH1            = "Supprimer le commentaire : {$params['id']}";
$pageDescription    = "Ici c'est la page de suppression de commentaire";

if(!(new AdminModel())->isAuthenticatedAdmin() && !(new UserModel())->isAuthenticatedUser()){
    header('Location: ' . $router->url('login_user') . '?security=1');
}

$id    = (int)$params['id'];
$pdo   = Database::dbConnect();
$table = new CommentTable($pdo);

if(isset($_SESSION['admin']))
{
    $admin        = (new AdminTable())->findOne((int)$_SESSION['admin']);
    $commentAdmin = $table->findCommentAdmin($id, $admin->getId());
}
elseif(isset($_SESSION['user']))
{
    $user         = (new UserTable())->findOne((int)$_SESSION['user']);
    $commentUser  = $table->findCommentUser($id, $user->getId());
}


if(isset($commentAdmin) && !is_string($commentAdmin)){
    if($commentAdmin->getId() === $id){
        $table->delete($id);
        header('Location: '. $router->url('post', [ 'id' => $params['id_post'], 'slug' => $params['slug_post']]) . '?delete=1');
        exit();
    }
}
elseif(isset($commentUser) && !is_string($commentUser)){
    if($commentUser->getId() === $id){
        $table->delete($id);
        header('Location: '. $router->url('post', [ 'id' => $params['id_post'], 'slug' => $params['slug_post']]) . '?delete=1');
        exit();
    }
}
else{
    header('Location: '. $router->url('post', [ 'id' => $params['id_post'], 'slug' => $params['slug_post']]) . '?paramFalse=1');
    exit();
}