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

<?php if(!empty($errors)) : ?>
    <div class="btn btnRed">
        <p>Vos coordonnées n'ont pas pu être enregistrer</p>
    </div>
<?php endif ?>

<section class="card_home_flex">
    <!-- afficher tous les articles -->
    <?php foreach($posts as $post): ?>
        <div class="card_home">
            <h4>Titre <?= $post->getName() ?></h4>
            <p><?= $post->getCreatedAt()->format('d F Y') ?></p>
            <p><?= utf8_encode($post->getExcerpt()) ?></p>
            <button class="voir_plus">
                <a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>">Voir plus</a>
            </button>
        </div>
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