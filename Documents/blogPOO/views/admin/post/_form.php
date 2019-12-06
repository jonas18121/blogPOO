<?php 
$pdo        = Database::dbConnect();
$table      = new CategoryTable($pdo);
$category   = $table->all();
?>
<div class="divForm">
    <form action='' method='post'>
        <?= $form->inpute('name', 'Titre'); ?>
        <?= $form->inpute('slug', 'Mot clé'); ?>
        <?= $form->textarea('content', 'Contenu'); ?>
        <?= $form->inpute('created_at', 'Date de publication'); ?>
        <?= $form->select('category_id', 'Category', $category); ?>
        <?= $form->inpute('admin_i'); ?>
        <input type="submit" value="<?= ($model->getId() !== null) ? 'Modifier' : 'Créer' ?> ">
    </form>
</div>