<?php

if (session_status() === PHP_SESSION_ACTIVE) {
    session_start();
    if(isset($_SESSION['auth'])){
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

    $pdo        = Database::dbConnect();
    $table      = new UserTable($pdo);
    $validator  = new UserValidator($_POST,$table);

    if($table->exist('email', $_POST['email']) === true){
        $errors['email'][] = 'Ce mail est déjà utilisé, écrivez un autre .';
    }
    else{

        if($validator->validate() === true){
            
            $model->setUsername($_POST['username'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password']);
            try{
                $table->register([
                    'username'  => $model->getUsername(),
                    'email'     => $model->getEmail(),
                    'password'  => $model->getPassword() 
                ]);
                $success = true;
                header('Location: ' . $router->url('login') . '?created=1');
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
    <a href="<?= $router->url('login') ?>" class='btn'>Se connecter</a>
</div>
    
<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>Vos coordonnées n'ont pas pu être enregistrer</p>
    </div>
<?php endif ?>

<div class="divForm">
    <form action='' method='post'>
        <?= $form->inpute('username', 'Votre nom') ?>
        <?= $form->inpute('email', 'Votre email') ?>
        <?= $form->inpute('password', 'Votre mot de passe') ?>
        <input type="submit" value="Valider">
    </form>
</div>