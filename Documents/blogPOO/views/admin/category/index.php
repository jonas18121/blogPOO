<?php 
$pageTitle       = "Administration";
$titleH1         = 'Gestion des categories';
$pageDescription = "Ici c'est la page d'administration";

Auth::check();

$pdo   = Database::dbConnect();
$table = new CategoryTable($pdo);
$model = (new CategoryTable($pdo))->all();

$link  = $router->url('admin_posts');
?>

<?php if(isset($_GET['created']) && $_GET['created'] === '1') : ?>
    <div class="btn btnGreen">
        <p>L'article à bien été enregistrer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['delete']) && $_GET['delete'] === '1') : ?>
    <div class="btn btnGreen">
        <p>L'enregistrement a bien été supprimé</p>
    </div>
<?php endif ?>

<div class="responsive_table">
<table class="table">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th>URL</th>
        <th>
            <a href="<?= $router->url('admin_category_new') ?>" class="btn" >Créer une nouvelle category</a>
        </th>
    </thead>
    <tbody>
        <?php foreach($model as $item) : ?>
        <tr>
            <td>#<?= $item->getid() ?></td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $item->getId()]); ?>">
                    <?= htmlentities($item->getName()) ?>
                </a>    
            </td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $item->getId()]); ?>">
                    <?= htmlentities($item->getSlug()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $item->getId()]); ?>" class="btn">
                    Editer
                </a> 
                <!-- securiter de l'url de suppresion -->
                <form action="<?= $router->url('admin_category_delete', ['id' => $item->getId()]); ?>" method="POST" 
                    onsubmit="return confirm('Voulez vous vraiment supprimer cette category ?')" class="formDelete">
                    <button type='submit' class="btn btnRed">supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
        <tr></tr>
    </tbody>
</table>
</div>
