<?php
$pageTitle       = "Création d'un nouvelle article";
$titleH1         = "Créer un article";
$pageDescription = "Ici c'est la page de création d'un nouvelle article";

if(!(new AdminModel())->isAuthenticatedAdmin()) header('Location: ' . $router->url('login_user') . '?security=1');

$success    = false;
$errors     = [];
$model      = new PostModel($router);
$admin      = (new AdminTable())->findOne((int)$_SESSION['admin']);
$adminI     = $admin->getId();
$pdo        = Database::dbConnect();
$table      = new PostTable($pdo);

$model->setAdminI($adminI);
if(!empty($_POST)){
    $_POST['name']    = trim($_POST['name']);
    $_POST['slug']    = trim($_POST['slug']);
    $_POST['content'] = trim($_POST['content']);

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
                'admin_i'       => $model->getAdminI()
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

<?php if(isset($_GET['NoCategoryForArticle']) && $_GET['NoCategoryForArticle'] === '1') : ?>
    <div class="btn btnRed"> 
        <p>
            Cette catégorie n'est lier à aucun article pour l'instant. <br>
            Créer un article pour cette catégorie.
        </p>
    </div>
<?php endif ?>

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
