<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['user']) || isset($_SESSION['admin'])){
        header('Location: ' . $router->url('home'));
        exit();
    }
}

$pageTitle          = "Inscription";
$titleH1            = "S'inscrire";
$pageDescription    = "Ici c'est la page d'inscription";
$model              = new UserModel();
$errors             = [];

if(!empty($_POST)){
    $_POST['name']      = trim($_POST['name']);
    $_POST['email']     = trim($_POST['email']);
    $_POST['password']  = trim($_POST['password']);
    $_POST['password2'] = trim($_POST['password2']);

    $model->setName($_POST['name']);//on réaffiche le mot qui a été écrit dans cet input

    $pdo        = Database::dbConnect();
    $table      = new UserTable($pdo);
    $validator  = new UserValidator($_POST,$table);

    if($table->exist('email', $_POST['email']) === true){
        $errors['email'][] = 'Ce mail est déjà utilisé, écrivez un autre .';
    }
    else{

        if($validator->validate() === true){
            
            $model->setName($_POST['name'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password']);
            try{
                $table->register([
                    'name'      => $model->getName(),
                    'email'     => $model->getEmail(),
                    'password'  => $model->getPassword() 
                ]);
                $success = true;
                header('Location: ' . $router->url('login_user') . '?created=1');
                exit();
            } 
            catch(NotFoundException $e){
                $errors['errors'] = "L'identifiant ou le mot de passe est incorrect ";
            }
        }else{
            $errors = $validator->errors();
        }
    }
}

$form = new Form($model, $errors);
?>
<div class='btnConnecte'>
    <a href="<?= $router->url('login_user') ?>" class='btn'>Se connecter</a>
</div>
    
<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>Vos coordonnées n'ont pas pu être enregistrer</p>
    </div>
<?php endif ?>

<div class="divForm">
    <form action='' method='post'>
        <?= $form->inpute('name', 'Votre nom') ?>
        <?= $form->inpute('email', 'Votre email') ?>
        <?= $form->inpute('password', 'Votre mot de passe') ?>
        <?= $form->inpute('password2', 'Comfirmer votre mot de passe') ?>
        <input type="submit" value="Valider">
    </form>
</div>