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
        <title><?= $pageTitle ?? 'Rewrite url' ?></title>
        <meta name="description" content="<?= $pageDescription ?? '' ?>"> 
        <link rel="stylesheet" type="text/css" media="all" href="/css/normalize.css">
        <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    </head>
    <body>
        <header>
            <div class="topnav" id="myTopnav">
                <a href="<?= $router->url('home') ?>" class="active">Home</a>
                <a href="<?= $router->url('contacte') ?>">Contacte</a>
                <?php $auth = new UserModel() ?>
                <?php if(!$auth->isAuthenticatedUser()) : ?>
                    <a href="<?= $router->url('login') ?>">Se connecter</a>
                <?php endif ?>

                <?php if(isset($_SESSION)) : ?>
                    <?php if(array_key_exists('auth',$_SESSION) && isset($_SESSION['auth']) && !empty($_SESSION['auth'])) : ?>
                        <a href="<?= $router->url('admin_posts') ?>">Gestion des articles</a>
                        <a href="<?= $router->url('admin_categories') ?>">Gestion des categories</a>
                        <form action="<?= $router->url('logout') ?>" method="post" class="form_menu">
                            <button type="submit" style=" border:none;" >Se déconnecter</button>
                        </form>
                        <form action="<?= $router->url('delete_user',['id' => $_SESSION['auth']]) ?>" method="post" class="form_menu"
                            onsubmit="return confirm('Voulez vous vraiment supprimer votre compte ? \nTous vos articles seront également supprimer.')">
                            <button type="submit" style=" border:none;" >Supprimer son compte</button>
                        </form><!-- background:transparent; -->
                    <?php endif ?>
                <?php endif ?>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">Menu</a>
            </div>
            <div class="clear"></div>
        </header>
        <main>
            <section class="marge_content_layout">
                <h1><?= $titleH1 ?? '' ?></h1>