<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id             = (!isset($params['id'])) ? ((isset($params['id_post'])) ? (int)$params['id_post'] : '') : (int)$params['id'];
$slug           =  (!isset($params['slug'])) ? ((isset($params['slug_post'])) ? $params['slug_post'] : '') : $params['slug'];
$pdo            = Database::dbConnect();

$postTable      = new PostTable($pdo);
$post           = $postTable->findOne($id);
(is_string($post)) ? header('Location: ' . $router->url('404')) : '';

$admin          = new AdminModel();
$adminTable     = new AdminTable();
$adminAuthor    = $adminTable->findOne($post->getAdminI());

$adminCurrent   = (isset($_SESSION['admin'])) ? $adminTable->findOne((int)$_SESSION['admin']) : '';
$postAdmin      = (isset($adminCurrent) && !is_string($adminCurrent)) ? $postTable->findPostAdmin($id, $adminCurrent->getId()) : '';

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


// ----------commentaire----------------//

$success    = false;
$errors     = [];

$userTable  = new UserTable();

if(isset($_SESSION['admin'])){
    //$admin      = $adminTable->findOne((int)$_SESSION['admin']);
    $adminI     = $adminCurrent->getId();
}
elseif(isset($_SESSION['user'])){
    $user       = $userTable->findOne((int)$_SESSION['user']);
    $userI      = $user->getId();
}

$model          = new CommentModel();
$CommentTable   = new CommentTable($pdo);
$comments       = $CommentTable->findCommentByPost((int)$id);

if(isset($adminI) && (!empty($adminI) || $adminI !== NULL)){
    $model->setAdminI($adminI)
        ->setAuthor($adminCurrent->getName())
        ->setUserI(NULL);
}
elseif(isset($userI) && (!empty($userI) || $userI !== NULL)){
    $model->setAdminI(NULL)
        ->setAuthor($user->getName())
        ->setUserI($userI);
}

$model->setIdPost($id)
    ->setSlugPost($slug);

    
if(!empty($_POST)){

    $_POST['content'] = trim($_POST['content']);
    $validator        = new CommentValidator($_POST,$CommentTable, $model->getId());
            
    if($validator->validate() === true){
    
        $model->setContent($_POST['content']);

        try{
            if(isset($admin) && $admin->isAuthenticatedAdmin() || isset($user) && $user->isAuthenticatedUser()){
                $CommentTable->create([
                    'content'   => $model->getContent(),
                    'admin_i'   => $model->getAdminI(),
                    'author'    => $model->getAuthor(),
                    'user_i'    => $model->getUserI(),
                    'id_post'   => $model->getIdPost(),
                    'slug_post' => $model->getSlugPost()
                ]);
            }
            $success = true;
            header('Location: ' . $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) . '?createdComment=1');
            exit();
        } 
        catch(Exception $e){
            $errors['errors'] = "Le commentaire n'a pas pu être enregistrer ";
        }
    }else{
        $errors = $validator->errors();
    }
}
$form = new Form($model, $errors);

?>

<?php if(isset($success) && $success === true) : ?>
    <div class="btn btnGreen">
        <p>Le commentaire à bien été créer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['createdComment']) && $_GET['createdComment'] === '1') : ?>
    <div class="btn btnGreen">
        <p>Le commentaire à bien été créer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['editedComment']) && $_GET['editedComment'] === '1') : ?>
    <div class="btn btnGreen">
        <p>Le commentaire à bien été modifier</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['delete']) && $_GET['delete'] === '1') : ?>
    <div class="btn btnGreen">
        <p>Le commentaire à bien été supprimer</p>
    </div>
<?php endif ?>

<?php if(isset($_GET['paramFalse']) && $_GET['paramFalse'] === '1') : ?>
    <div class="btn btnRed"> 
        <p>La modification est incorrecte</p>
    </div>
<?php endif ?>

<?php if($admin->isAuthenticatedAdmin() && isset($postAdmin)) : ?>
    <?php if(!is_string($postAdmin)) : ?>
        <?php if(isset($errorOneCategorie)) : ?>
            <div class="btn btnRed">
                <p><?= $errorOneCategorie ?></p>
            </div>
        <?php endif ?> 
    <?php endif ?>
<?php endif ?>

<article class="article_show">
    <h2><?= $post->getName() ?></h2>
    <p><?= $post->getCreatedAt()->format('d F Y') ?></p>
    <p>Auteur : <strong><?= $adminAuthor->getName() ?></strong></p>

    <?php if(isset($oneCategories) && !empty($oneCategories)) : ?>
        <?php foreach($oneCategories as $oneCategorie): ?>
            <p>Catégorie : <?= $oneCategorie->getName() ?></p>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(isset($allCategories) && !empty($allCategories)) : ?>
        <p>Liste des autres catégoies</p>
        <?php foreach($allCategories as $k => $category): ?>
            <?php if($k > 0) echo ',' ; ?><!-- si le $k > 0 on met une virule -->
            <?php $category_url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]); ?>
            <a href="<?= $category_url ?>"><?= $category->getName() ?></a>
        <?php endforeach ?>
    <?php endif ?>
    <hr>

    <div class="content_article">
        <p><?= htmlentities($post->getFormattedContent()) ?></p>
    </div>
</article>

<!-- -----------commentaire------------ -->

<section class="comment">
    <h3>Commentaires</h3>

    <div class="divForm">
        <form action="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]); ?>" method="post">

            <?= $form->textarea('content', 'Ecrire un commentaire'); ?>
            <?= $form->inpute('admin_i');?>
            <?= $form->inpute('author'); ?>
            <?= $form->inpute('user_i'); ?>
            <?= $form->inpute('id_post'); ?><!-- l'id de l'article --> 
            <?= $form->inpute('slug_post'); ?><!-- le slug de l'article --> 

            <input type="submit" value="Valider">
        </form>
    </div>

    <?php if(isset($comments) && !empty($comments)) : ?>
        <?php foreach($comments as $comment) : ?>
        
            <article class="show_comment">
                <p>
                    Auteur : <strong>
                        <?php
                            if($comment->getAdminI() > $comment->getUserI())
                            {
                                echo $comment->getAuthor() . ' (Administrateur)';
                            }
                            elseif($comment->getUserI() > $comment->getAdminI())
                            {
                                echo $comment->getAuthor();
                            }
                        ?>
                        </strong> à publier ce commentaire le  
                        <?php
                            /*usort(
                                $comments,
                                function($a,$b)
                                {
                                    $da = $a->getCreatedAt();
                                    $db = $b->getCreatedAt();
            
                                    #return $a <=> $b;
                                    return $da->getTimestamp() - $db->getTimestamp();
                                }
                            );*/
                        ?>
                        <strong><?= $comment->getCreatedAt()->format('d F Y à H:i:s') ?></strong> 

                        <?php if(isset($_SESSION['admin']) && $comment->getAdminI() === $_SESSION['admin']) : ?>
                            <span>(<a href="<?= $router->url('comment_edit', ['id' => $comment->getId(), 'slug_post' => $model->getSlugPost(), 'id_post' => $model->getIdPost()]) ?>">modifier</a>)</span> 
                            <span>
                                (<a href="<?= $router->url('comment_delete', ['id' => $comment->getId(), 'slug_post' => $model->getSlugPost(), 'id_post' => $model->getIdPost()]); ?>" 
                                    onclick = "return confirm('Voulez vous vraiment supprimer ce commentaire ?')">
                                    supprimer
                                </a>)
                            </span>
                        <?php endif ?>

                        <?php if(isset($_SESSION['user']) && $comment->getUserI() === $_SESSION['user']) : ?>
                            <span>(<a href="<?= $router->url('comment_edit', ['id' => $comment->getId(), 'slug_post' => $model->getSlugPost(), 'id_post' => $model->getIdPost()]); ?>">modifier</a>)</span> 
                            <span>
                                (<a href="<?= $router->url('comment_delete', ['id' => $comment->getId(), 'slug_post' => $model->getSlugPost(), 'id_post' => $model->getIdPost()]); ?>" 
                                    onclick = "return confirm('Voulez vous vraiment supprimer ce commentaire ?')">
                                    supprimer
                                </a>)
                            </span>
                        <?php endif ?>
                </p>
                <p><?= htmlentities($comment->getContent()) ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif ?>
</section>