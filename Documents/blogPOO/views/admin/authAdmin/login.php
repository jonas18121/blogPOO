<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}

$pageTitle          = "Connexion";
$titleH1            = "Connection Administrateur";
$pageDescription    = "Ici c'est la page de connexion pour d'administration";
$model              = new AdminModel();
$errors             = []; 

if(!empty($_POST)){

    $_POST['email']     = trim($_POST['email']);
    $_POST['password']  = trim($_POST['password']);

    $pdo        = Database::dbConnect();
    $table      = new AdminTable($pdo);
    $validator  = new AdminValidator($_POST,$table);
    
    if($validator->validate() === true){
        try{
            $admin = $table->login($_POST['email']);
            $admin->getPassword();
            $_POST['password'];

            if(password_verify($_POST['password'], $admin->getPassword()) === false){
                $errors['password'][] = "Le mot de passe est incorrect "; 
            }
            else{
                session_start();
                $_SESSION['admin'] = $admin->getId();
                header('Location: ' . $router->url('admin_posts'));
                exit();
            }
        } 
        catch(NotFoundException $e){
            $errors['errors'] = "L'identifiant ou le mot de passe est incorrect ";
        }
    }else{
        $errors = $validator->errors();
    }
}
$form = new Form($model, $errors);
?>

<div class='btnConnecte'>
    <a href="<?= $router->url('register_admin') ?>" class='btn'>S'inscrire</a>
</div>

<?php if(isset($_GET['delete_admin']) && $_GET['delete_admin'] === '1') : ?>
    <div class="btn btnGreen">
        <p>Votre compte a bien été supprimer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['created']) && $_GET['created'] === '1') : ?>
    <div class="btn btnGreen">
        <p>Vous avez bien été enregistrer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['security']) && $_GET['security'] === '1') : ?>
    <div class="btn btnRed">
        <p>Vous ne pouvez pas vous connecter à cettte page.</p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>
            Vous avez un champ qui n'est pas correctement remplit,<br>
            ou<br>
            vous n'êtes pas inscris.
        </p>
    </div>
<?php endif ?>

<div class="divForm">
    <form action="<?= $router->url('login_admin') ?>" method="POST">
        <?= $form->inpute('email', 'Email') ?>
        <?= $form->inpute('password', 'Mot de passe') ?>
        <input type="submit" value="Se connecter">
    </form>
    <p>Si vous n'ètes pas inscrit : <a href="<?= $router->url('register_admin') ?>">cliquez ici</a></p>
</div>