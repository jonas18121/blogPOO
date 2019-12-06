<?php
$pageTitle          = "Suppression de catégories";
$titleH1            = "Supprimer la catégory : {$params['id']}";
$pageDescription    = "Ici c'est la page de suppression de catégories";

//Auth::check();
if(!(new AdminModel())->isAuthenticatedAdmin()) header('Location: ' . $router->url('login_user') . '?security=1');

$pdo    = Database::dbConnect();
$table  = new CategoryTable($pdo);
$table->delete((int)$params['id']);
header('Location: '. $router->url('admin_categories') . '?delete=1');
exit();