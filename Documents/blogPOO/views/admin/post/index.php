<?php 
$pageTitle       = "Gestion de mes articles";
$titleH1         = $pageTitle;
$pageDescription = "Ici c'est la page d'administration";

Auth::check();

$pdo    = Database::dbConnect();
$table  = new PostTable($pdo, $router);
$link   = $router->url('admin_posts');
[$items, $pagination] = $table->findPaginatedAdmin($_SESSION['auth']);

//réécrire l'url sans un paramètre avec ?page=[a-zA-Z] ou ?page=[0-9]{1,}[a-zA-Z]
if(isset($_GET['page'])){
    if(!preg_match("/^[0-9]{1,}+$/", $_GET['page'])){
        $uri = explode('?', $_SERVER["REQUEST_URI"])[0];
        $get = $_GET;
        unset($get['page']);
        $query = http_build_query($get);
        if(!empty($query)){
            $uri = $uri . '?' . $query;
        }
        http_response_code(301);
        header('Location: ' . $uri);
        exit();
    }
}
?>

<?php if(isset($_GET['paramFalse']) && $_GET['paramFalse'] === '1') : ?>
    <div class="btn btnRed"> 
        <p>La modification est incorrecte</p>
    </div>
<?php endif ?>

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
        <th>
            <a href="<?= $router->url('admin_post_new') ?>" class="btn" >Créer un article</a>
        </th>
    </thead>
    <tbody>
        <?php foreach($items as $item) : ?>
        <tr>
            <td data-title="#">#<?= $item->getId() ?></td>
            <td data-title="Titre">
                <a href="<?= $router->url('post', ['slug' => $item->getSlug(),'id' => $item->getId()]); ?>">
                    <?= htmlentities($item->getName()) ?>
                </a>    
            </td>
            <td data-title="Créer un article">
                <a href="<?= $router->url('admin_post', ['id' => $item->getId()]); ?>" class="btn">
                    Editer
                </a> 
                <!-- securiter de l'url de suppresion -->
                <form action="<?= $router->url('admin_post_delete', ['id' => $item->getId()]); ?>" method="POST" 
                    onsubmit="return confirm('Voulez vous vraiment supprimer cette article ?')" class="formDelete">
                    <button type='submit' class="btn btnRed">supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
</div>

<div class="pagination_flex">
    <div>
        <?= $pagination->previousLink($link); ?>
    </div>
    <div>
        <?=  $pagination->nextLinkAdmin($link,$_SESSION['auth']); ?>
    </div>
</div>