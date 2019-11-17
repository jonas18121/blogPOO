<?php
$pageTitle          = "Modification d'article";
$titleH1            = "Editer l'article : {$params['id']}";
$pageDescription    = "Ici c'est la page de modification d'article";

Auth::check();

$id         = (int)$params['id'];
$pdo        = Database::dbConnect();
$table      = new PostTable($pdo);
$model      = $table->findOne($id);
(is_string($model)) ? header('Location: ' . $router->url('404')) : '';
$success    = false;
$errors     = []; 
$user       = (new UserTable())->findOne((int)$_SESSION['auth']);
$postUser   = $table->findPostUser($id, $user->getId());

if(isset($postUser) && is_string($postUser)){
    header('Location: '. $router->url('admin_posts'));
}

if(!empty($_POST)){

    $category  = (new CategoryTable)->oneCategory((int)$_POST['category_id']);
    (is_string($category)) ? header('Location: ' . $router->url('404')) : '';
    $validator = new PostValidator($_POST,$table, $category, $model->getId());
   
    if($validator->validate() === true){

        $model->setName($_POST['name'])
            ->setSlug($_POST['slug'])
            ->setContent($_POST['content'])
            ->setCreatedAt($_POST['created_at'])
            ->setCategoryId($_POST['category_id']);

        try{
            $table->update([
                'name'          => $model->getName(),
                'slug'          => $model->getSlug(),
                'content'       => $model->getContent(),
                'created_at'    => $model->getCreatedAt()->format("Y-m-d H:i:s"),
                'category_id'   => $model->getCategoryId(),
                'user_i'        => $model->getUserI()
            ], $model->getId());
            $success = true;
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

<?php if($success) : ?>
    <div class="btn btnGreen">
        <p>L'article à bien été modifier</p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>L'article n'a pas pu être modifier</p>
    </div>
<?php endif ?>

<?php require_once '_form.php'; ?>

