<?php 
$pageTitle          = "Acceuil";
$titleH1            = $pageTitle;
$pageDescription    = "Ici c'est la page d'acceuil";
$pdo                = Database::dbConnect();
$postTable          = new PostTable($pdo, $router);
[$posts, $pagination] = $postTable->findPaginated();// ou list($posts, $pagination)

$link = $router->url('home');

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

<?php if(isset($_GET['security']) && $_GET['security'] === '1') : ?>
    <div class="btn btnRed"> 
        <p> Cette catégorie n'est lier à aucun article pour l'instant. </p>
    </div>
<?php endif ?>

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>Vos coordonnées n'ont pas pu être enregistrer</p>
    </div>
<?php endif ?>

<section class="card_home_flex">
    <!-- afficher tous les articles -->
    <?php foreach($posts as $post): ?>
        <article class="card_home">
            <h2><?= $post->getName() ?></h2>
            <p><?= $post->getCreatedAt()->format('d F Y') ?></p>
            <p><?= $post->getExcerpt() ?></p>
            <button class="voir_plus">
                <a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>">Voir plus</a>
            </button>
        </article>
    <?php endforeach; ?>
</section>

<!-- afficher la pagination -->
<div class="pagination_flex">
    <div>
        <?= $pagination->previousLink($link) ?>
    </div>
    <div>
        <?= $pagination->nextLink($link) ?>
    </div>
</div>