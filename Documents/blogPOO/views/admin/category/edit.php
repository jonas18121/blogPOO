<?php
$pageTitle          = "Modification de catégories";
$titleH1            = "Editer la catégory : {$params['id']}";
$pageDescription    = "Ici c'est la page de modification de catégories";

Auth::check();

$pdo        = Database::dbConnect();
$table      = new CategoryTable($pdo);
$model      = $table->findOne($params['id']);
(is_string($model)) ? header('Location: ' . $router->url('404')) : '';
$success    = false;
$errors     = [];

if(!empty($_POST)){

    $validator = new CategoryValidator($_POST,$table, $model->getId());
    $model->setName($_POST['name'])
        ->setSlug($_POST['slug']);
    
    if($validator->validate() === true){
        $table->update([
            'name' => $model->getName(),
            'slug' => $model->getSlug()
        ], $model->getId());
        $success = true;
    }else{
        $errors  = $validator->errors();
    }
}
$form = new Form($model, $errors); 
?>

<?php if($success) : ?>
    <div class="btn btnGreen">
        <p>La category à bien été modifier</p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>La category n'a pas pu être modifier</p>
    </div>
<?php endif ?>

<?php require_once '_form.php'; ?>

