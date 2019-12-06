<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success    = false;
$errors     = [];
//$id         = (!isset($params['id'])) ? ((isset($_GET['id_post'])) ? (int)$_GET['id_post'] : '') : (int)$params['id'];
//$slug       = (!isset($params['slug'])) ? ((isset($_GET['slug_post'])) ? $_GET['slug_post'] : '') : $params['slug'];
$id         = (int)$params['id_post'];
$slug       = $params['slug_post'];


$pdo        = Database::dbConnect();
$postTable  = new PostTable($pdo);
$post       = $postTable->findOne((int)$id);
//(is_string($post)) ? header('Location: ' . $router->url('404')) : '';

$adminTable = new AdminTable();
$userTable  = new UserTable();

if(isset($_SESSION['admin'])){
    $admin      = $adminTable->findOne((int)$_SESSION['admin']);
    $adminI     = $admin->getId();
    $userI      = $userTable->all();
    //echo'<pre>'; var_dump($userI);die; echo'</pre>';
}
if(isset($_SESSION['user'])){
    $user       = $userTable->findOne((int)$_SESSION['user']);
    $userI      = $user->getId();
    //$adminI     = 0;
}
//var_dump($params);die;
$model          = new CommentModel();
$CommentTable   = new CommentTable($pdo);
$comments       = $CommentTable->findCommentByPost((int)$id);

if(isset($adminI) && (!empty($adminI) || $adminI !== NULL)){
    $model->setAdminI($adminI)
        ->setUserI(NULL);
}
elseif(isset($userI) && (!empty($userI) || $userI !== NULL)){
    $model->setAdminI(NULL);
    $model->setUserI($userI);
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
        $params['id']   = $model->getIdPost();
        $params['slug'] = $model->getSlugPost();
        require_once dirname(__DIR__) . '../exo/blog/show.php';
    }
}
$form = new Form($model, $errors);


?>
<section class="comment">
    <h3>Commentaires</h3>

    <div class="divForm">
        <form action="<?= $router->url('comment_new'); ?>" method="post">

            <?= $form->textarea('content', 'Ecrire un commentaire'); ?>
            <?= $form->inpute('admin_i');?>
            <?= $form->inpute('user_i'); ?>
            <?= $form->inpute('id_post'); ?><!-- l'id de l'article --> 
            <?= $form->inpute('slug_post'); ?><!-- le slug de l'article --> 

            <input type="submit" value="Valider">
        </form>
    </div>

    <?php foreach($comments as $comment) : ?>
        <article class="show_comment">
            <p>
                Auteur : <strong>
                    <?php
                        if($comment->getAdminI() === $comment->getId())
                        {
                            echo $comment->getName() . ' (Administrateur)';
                        }
                        elseif($comment->getUserI() === $comment->getId())
                        {
                            echo $comment->getName();
                        }
                    ?>
                    </strong> à publier ce commentaire le  
                    <?php
                        usort(
                            $comments,
                            function($a,$b)
                            {
                                $da = $a->getCreatedAt();
                                $db = $b->getCreatedAt();
            
                                #return $a <=> $b;
                                return $da->getTimestamp() - $db->getTimestamp();
                            }
                        );
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
</section>

