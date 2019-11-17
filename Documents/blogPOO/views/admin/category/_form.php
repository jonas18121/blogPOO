<div class="divForm">
    <form action='' method='post'>
        <?= $form->inpute('name', 'Titre'); ?>
        <?= $form->inpute('slug', 'Mot clé'); ?>
        <input type="submit" value="<?= ($model->getId() !== null) ? 'Modifier' : 'Créer' ?> ">
    </form>
</div>