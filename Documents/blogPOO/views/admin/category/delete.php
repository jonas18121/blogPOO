<?php
$pageTitle          = "Suppression de catégories";
$titleH1            = "Supprimer la catégory : {$params['id']}";
$pageDescription    = "Ici c'est la page de suppression de catégories";

Auth::check();

$pdo    = Database::dbConnect();
$table  = new CategoryTable($pdo);
$table->delete($params['id']);
header('Location: '. $router->url('admin_categories') . '?delete=1');