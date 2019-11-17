<?php
$pageTitle       = "Création d'un nouvelle article";
$titleH1         = "Créer un article";
$pageDescription = "Ici c'est la page de création d'un nouvelle article";

Auth::check();

$success    = false;
$errors     = [];
$model      = new PostModel($router);
$userI      = (new UserTable())->findIdAdmin((int)$_SESSION['auth']);
$pdo        = Database::dbConnect();
$table      = new PostTable($pdo);

$model->setUserI($userI);
if(!empty($_POST)){

    $category   = (new CategoryTable)->oneCategory((int)$_POST['category_id']);
    (is_string($category)) ? header('Location: ' . $router->url('404')) : ''; 
    $validator  = new PostValidator($_POST,$table,$category, $model->getId());
            
    if($validator->validate() === true){

        $model->setName($_POST['name'])
            ->setSlug($_POST['slug'])
            ->setContent($_POST['content'])
            ->setCreatedAt($_POST['created_at'])
            ->setCategoryId($_POST['category_id']);

        try{
            $table->create([
                'name'          => $model->getName(),
                'slug'          => $model->getSlug(),
                'content'       => $model->getContent(),
                'created_at'    => $model->getCreatedAt()->format('Y-m-d H:i:s'),
                'category_id'   => $model->getCategoryId(),
                'user_i'        => $model->getUserI()
            ]);
            $success = true;
            header('Location: ' . $router->url('admin_posts') . '?created=1');
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

<?php if(isset($_GET['NoCreatedArticle']) && $_GET['NoCreatedArticle'] === '1') : ?>
    <div class="btn btnGreen"> 
        <p>Créer un article!</p>
    </div>
<?php endif ?>

<?php if($success) : ?>
    <div class="btn btnGreen">
        <p>L'article à bien été enregistrer</p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>L'article n'a pas pu être enregistrer</p>
    </div>
<?php endif ?>

<?php require_once '_form.php'; ?>
