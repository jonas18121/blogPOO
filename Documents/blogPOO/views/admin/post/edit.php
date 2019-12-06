<?php
$pageTitle          = "Modification d'article";
$titleH1            = "Editer l'article : {$params['id']}";
$pageDescription    = "Ici c'est la page de modification d'article";

//Auth::check();
if(!(new AdminModel())->isAuthenticatedAdmin()) header('Location: ' . $router->url('login_user') . '?security=1');

$id          = (int)$params['id'];
$pdo         = Database::dbConnect();
$table       = new PostTable($pdo);
$model       = $table->findOne($id);
(is_string($model)) ? header('Location: ' . $router->url('404')) : '';
$success     = false;
$errors      = []; 
$admin       = (new AdminTable())->findOne((int)$_SESSION['admin']);
$postAdmin   = $table->findPostAdmin($id, $admin->getId());

if(isset($postAdmin) && is_string($postAdmin)){
    header('Location: '. $router->url('admin_posts'));
}

if(!empty($_POST)){
    $_POST['name']    = trim($_POST['name']);
    $_POST['slug']    = trim($_POST['slug']);
    $_POST['content'] = trim($_POST['content']);

    $category  = (new CategoryTable())->oneCategory((int)$_POST['category_id']);
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
                'admin_i'        => $model->getAdminI()
            ], $model->getId());
            $success = true;
            header('Location: ' . $router->url('admin_posts') . '?edited=1');
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

