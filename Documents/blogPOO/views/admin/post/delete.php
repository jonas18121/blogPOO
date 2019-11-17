<?php
$pageTitle          = "Suppression d'article";
$titleH1            = "Supprimer l'article : {$params['id']}";
$pageDescription    = "Ici c'est la page de suppression d'article";

Auth::check();

$id       = (int)$params['id'];
$pdo      = Database::dbConnect();
$user     = (new UserTable())->findOne((int)$_SESSION['auth']);
$table    = new PostTable($pdo);
$postUser = $table->findPostUser($id, $user->getId());

if(isset($postUser) && !is_string($postUser)){
    if($postUser->getId() === $id){
        $table->delete($id);
        header('Location: '. $router->url('admin_posts') . '?delete=1');
    }
}else{
    header('Location: '. $router->url('admin_posts') . '?paramFalse=1');
}