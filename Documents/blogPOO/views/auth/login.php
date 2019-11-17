<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['auth'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}

$pageTitle          = "Connexion";
$titleH1            = "Se connecter";
$pageDescription    = "Ici c'est la page de connexion";
$model              = new UserModel();
$errors             = []; 

if(!empty($_POST)){
    $model->setUsername($_POST['username']);//on réaffiche le mot qui a été écrit dans cet input 

    $pdo        = Database::dbConnect();
    $table      = new UserTable($pdo);
    $validator  = new UserValidator($_POST,$table);
    
    if($validator->validate() === true){
        try{
            $user = $table->login($_POST['email']);
            $user->getPassword();
            $_POST['password'];

            if(password_verify($_POST['password'], $user->getPassword()) === false){
                $errors['password'][] = "Le mot de passe est incorrect "; 
            }
            else{
                session_start();
                $_SESSION['auth'] = $user->getId();
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
    <a href="<?= $router->url('register') ?>" class='btn'>S'inscrire</a>
</div>

<?php if(isset($_GET['delete_user']) && $_GET['delete_user'] === '1') : ?>
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
    <form action="<?= $router->url('login') ?>" method="POST">
        <?= $form->inpute('username', 'Nom d\' utilisateur') ?>
        <?= $form->inpute('email', 'Email') ?>
        <?= $form->inpute('password', 'Mot de passe') ?>
        <input type="submit" value="Se connecter">
    </form>
</div>