<?php
$pageTitle       = "Création d'une nouvelle categorie";
$titleH1         = "Créer une nouvelle categorie";
$pageDescription = "Ici c'est la page de création d'une nouvelle categorie";

Auth::check();

$success    = false;
$errors     = [];
$model      = new CategoryModel();

if(!empty($_POST)){
    $pdo        = Database::dbConnect();
    $table      = new CategoryTable($pdo);
    $validator  = new CategoryValidator($_POST, $table);

    $model->setName($_POST['name'])
        ->setSlug($_POST['slug']);
    
    if($validator->validate() === true){
        $table->create([
            'name' => $model->getName(),
            'slug' => $model->getSlug()
        ]);
        $success = true;
        header('Location: ' . $router->url('admin_categories') . '?created=1');
        exit();
    }else{
        $errors = $validator->errors();
    }
}

$form = new Form($model, $errors);
?>

<?php if($success) : ?>
    <div class="btn btnGreen">
        <p>La category à bien été enregistrer</p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>La category n'a pas pu être enregistrer</p>
    </div>
<?php endif ?>

<?php require_once '_form.php'; ?>
