<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- si $pageTitle existe on l'affiche sinon on affiche ça 'Rewrite url'-->
        <title><?= $pageTitle ?? 'BlogPOO' ?></title>
        <meta name="description" content="<?= $pageDescription ?? '' ?>"> 
        <link rel="stylesheet" type="text/css" media="all" href="/css/normalize.css">
        <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    </head>
    <body>
        <header >
            <div class="topnav" id="myTopnav">
                <a href="<?= $router->url('home') ?>" class="active">BlogPOO</a>
                <a href="<?= $router->url('contacte') ?>">Contact</a>
                <?php 
                    $admin = new AdminModel();
                    $user  = new UserModel();
                ?>
                <?php if(!$admin->isAuthenticatedAdmin() && !$user->isAuthenticatedUser()) : ?>
                    <a href="<?= $router->url('login_user') ?>">Se connecter</a>
                <?php endif ?>

                <!-- for user -->
                <?php if($user->isAuthenticatedUser()) : ?>
                    <form action="<?= $router->url('logout_user') ?>" method="post" class="form_menu">
                        <button type="submit" style=" border:none;" >Se déconnecter</button>
                    </form>
                    <form action="<?= $router->url('delete_user',['id' => $_SESSION['user']]) ?>" method="post" class="form_menu"
                        onsubmit="return confirm('Voulez vous vraiment supprimer votre compte ? \nTous vos commentaires seront également supprimer.')">
                        <button type="submit" style=" border:none;" >Supprimer son compte</button>
                    </form><!-- background:transparent; -->
                <?php endif ?>
 
                <!-- for admin -->
                <?php if(isset($_SESSION)) : ?>
                    <?php if(array_key_exists('admin',$_SESSION) && isset($_SESSION['admin']) && !empty($_SESSION['admin'])) : ?>
                        <a href="<?= $router->url('admin_posts') ?>">Gestion des articles</a>
                        <a href="<?= $router->url('admin_categories') ?>">Gestion des categories</a>
                        <form action="<?= $router->url('logout_admin') ?>" method="post" class="form_menu">
                            <button type="submit" style=" border:none;" >Se déconnecter</button>
                        </form>
                        <form action="<?= $router->url('delete_admin',['id' => $_SESSION['admin']]) ?>" method="post" class="form_menu"
                            onsubmit="return confirm('Voulez vous vraiment supprimer votre compte ? \nTous vos articles et vos commentaires seront également supprimer.')">
                            <button type="submit" style=" border:none;" >Supprimer son compte</button>
                        </form><!-- background:transparent; -->
                    <?php endif ?>
                <?php endif ?>
                
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">Menu</a>
            </div>
            <div class="clear"></div>
        </header>
        <main class="marge_content_layout">
            <section class="great_section">
                <h1><?= $titleH1 ?? '' ?></h1>