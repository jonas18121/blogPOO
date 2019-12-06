<?php
$pageTitle          = "Modification de commentaire";
$titleH1            = "Editer le commentaire : {$params['id']}";
$pageDescription    = "Ici c'est la page de modification de commentaire";

//Auth::check();
if(!(new AdminModel())->isAuthenticatedAdmin() && !(new UserModel())->isAuthenticatedUser()){
    header('Location: ' . $router->url('login_user') . '?security=1');
}

$id           = (int)$params['id'];
$slugPost     = $params['slug_post'];
$pdo          = Database::dbConnect();
$commentTable = new CommentTable($pdo);
$model        = $commentTable->findOne($id);

(is_string($model)) ? header('Location: ' . $router->url('404')) : '';
$success      = false;
$errors       = []; 

if(isset($_SESSION['admin'])){
    $adminTable = new AdminTable();
    $admin      = $adminTable->findOne((int)$_SESSION['admin']);
    $adminI     = $admin->getId();
}
elseif(isset($_SESSION['user'])){
    $userTable  = new UserTable();
    $user       = $userTable->findOne((int)$_SESSION['user']);
    $userI      = $user->getId();
}

if(isset($adminI) && (!empty($adminI) || $adminI !== NULL)){
    $model->setAdminI($adminI)
        ->setUserI(NULL)
        ->setSlugPost($slugPost);
}
elseif(isset($userI) && (!empty($userI) || $userI !== NULL)){
    $model->setAdminI(NULL);
    $model->setUserI($userI);
}

if(!empty($_POST)){ 
    $_POST['content'] = trim($_POST['content']);

    $model->setId($id)
        ->setSlugPost($slugPost)
        ->setContent($_POST['content'])
        ->setIdPost($_POST['id_post']);

    $validator = new CommentValidator($_POST,$commentTable, $model->getId());
   
    if($validator->validate() === true){

        $model->setContent($_POST['content']);

        try{
            $commentTable->update([
                'content'   => $model->getContent(),
                'admin_i'   => $model->getAdminI(),
                'user_i'    => $model->getUserI(),
                'id_post'   => $model->getIdPost(),
                'slug_post' => $model->getSlugPost()
            ], $model->getId());
            $success = true;
            header('Location: ' . $router->url('post', [ 'id' => $params['id_post'], 'slug' => $params['slug_post']] ) . '?editedComment=1');
            exit();
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

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>Le commentaire n'a pas pu être modifier</p>
    </div>
<?php endif ?>

<div class="divForm">
    <form action="" method="post">

        <?= $form->textarea('content', 'Modifier le commentaire'); ?>
        <?= $form->inpute('admin_i');?>
        <?= $form->inpute('user_i'); ?>
        <?= $form->inpute('id_post'); ?><!-- l'id de l'article --> 
        <?= $form->inpute('slug_post'); ?><!-- le slug de l'article --> 

        <input type="submit" value="Valider">
    </form>
</div>


