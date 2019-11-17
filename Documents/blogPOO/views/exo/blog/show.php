<?php
$id             = (int)$params['id'];
$slug           = $params['slug'];
$pdo            = Database::dbConnect();
$postTable      = new PostTable($pdo);
$post           = $postTable->findOne($id);
(is_string($post)) ? header('Location: ' . $router->url('404')) : '';
$userTable      = new UserTable();
$user           = $userTable->findOne($post->getUserI());
$categoryTable  = new CategoryTable($pdo);
$oneCategories  = $categoryTable->oneCategoryByPost($id);
$allCategories  = $categoryTable->all();
 
if(empty($oneCategories) && isset($oneCategories)) $errorOneCategorie = 'Sélectionnez une categorie pour votre article, dans Modifier un article';

//si le slug de l'article est différent du slug dans l'url, on fait une redirection
if($post->getSlug() !== $slug){
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
    http_response_code(301);
    header('Location: ' . $url);
}

$pageTitle          = "Article n° : {$post->getId()}";
$titleH1            = $pageTitle;
$pageDescription    = "Ici c'est la page d'une article";
?>

<?php if(isset($errorOneCategorie)) : ?>
    <div class="btn btnRed">
        <p><?= $errorOneCategorie ?></p>
    </div>
<?php endif ?>

<section class="article_show">
    <h4>Titre : <?= $post->getName() ?></h4>
    <p><?= $post->getCreatedAt()->format('d F Y') ?></p>
    <p>Auteur : <strong><?= $user->getUsername() ?></strong></p>

    <?php foreach($oneCategories as $oneCategorie): ?>
        <p>Catégorie : <?= $oneCategorie->getName() ?></p>
    <?php endforeach ?>

    <p>Liste des autres catégoies</p>
    <?php foreach($allCategories as $k => $category): ?>
        <?php if($k > 0) echo ',' ; ?><!-- si le $k > 0 on met une virule -->
        <?php $category_url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]); ?>
        <a href="<?= $category_url ?>"><?= $category->getName() ?></a>
    <?php endforeach ?>
    <hr>

    <div class="content_article">
        <p><?= htmlentities($post->getFormattedContent()) ?></p>
    </div>
</section>

