<?php if(!(new AdminModel())->isAuthenticatedAdmin()) header('Location: ' . $router->url('login_user') . '?security=1'); ?>

<div class="divForm">
    <form action="<?= $router->url('comment_new'); ?>" method="post">

        <?= $form->textarea('content', 'Ecrire un commentaire'); ?>
        <?= $form->inpute('created_at', 'Date de publication'); ?>
        <?= $form->inpute('admin_i');?>
        <?= $form->inpute('user_i'); ?>
        <?= $form->inpute('id_post'); ?><!-- l'id de l'article --> 
        <?= $form->inpute('slug_post'); ?><!-- le slug de l'article --> 

        <input type="submit" value="Valider">
    </form>
</div>