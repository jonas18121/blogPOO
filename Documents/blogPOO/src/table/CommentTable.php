<?php

class CommentTable extends Table {

    /** @var string $table - nom de la table */
    protected $table = 'comment';

    /** @var CommentModel $class - la classe PostModel  */
    protected $class = CommentModel::class;

    public function __construct($pdo = null){
        parent::__construct();
    } 

    /** Effacer tous les commentaires d'un admin précis deleteAllItemsOfItem
     * @param int $userI
     * @return void|throw
     */
    public function deleteAllCommentAdmin(int $adminI) : void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE admin_i = :admin_i");
        $ok = $query->execute(['admin_i' => $adminI]);
        if($ok === false){
            throw new Exception("Impossible de supprimer les enregistrement {$adminI} dans la table {$this->table}. ");
        }
    }

    /** Effacer tous les commentaires d'un article précis 
     * @param int $userI
     * @return void|throw
     */
    public function deleteAllCommentOfPost(int $id_post) : void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_post = :id_post");
        $ok = $query->execute(['id_post' => $id_post]);
        if($ok === false){
            throw new Exception("Impossible de supprimer les enregistrements {$id_post} dans la table {$this->table}. ");
        }
    }

    /** selectionne tous les commentaires que l'admin a fait sur un article ,
     * à partir de l'id du post et l'id de l'admin
     * @param int $id
     * @param int $adminI
     * @return $result - retourne une class ou lance une exception
     */
    public function findCommentByPostAdmin (int $id, int $adminI) 
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_post = :id_post AND admin_i = :admin_i ORDER BY id DESC";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'id_post' => $id,
            'admin_i' => $adminI
            ]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();
        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }

    /** selectionne tous les commentaire d'un article à partir de l'id du post
     * @param int $id
     * @return $result - retourne une class ou lance une exception
     */
    public function findCommentByPost (int $id) 
    {

        $sql = "SELECT * 
            FROM {$this->table} 
            WHERE id_post = :id_post ORDER BY {$this->table}.created_at DESC";

        $query = $this->pdo->prepare($sql);
        $query->execute(['id_post' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();

        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }

    /** selectionne un commentaire dans une table selectionner à partir de son id et l'id de l'admin
     * @param int $id - l'id du commentaire
     * @param int $adminI - l'id de l'admin
     * @return $result - retourne une class ou lance une exception
     */
    public function findCommentAdmin (int $id, int $adminI) 
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND admin_i = :admin_i";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'id' => $id,
            'admin_i' => $adminI
            ]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }

/*-----------------------------user-------------------------*/

    /** Effacer tous les commentaires d'un utilisateur précis deleteAllItemsOfItem
     * @param int $userI
     * @return void|throw
     */
    public function deleteAllCommentUser(int $userI) : void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE user_i = :user_i");
        $ok = $query->execute(['user_i' => $userI]);
        if($ok === false){
            throw new Exception("Impossible de supprimer les enregistrement {$userI} dans la table {$this->table}. ");
        }
    }

    /** selectionne un commentaire dans une table selectionner à partir de son id et l'id de l'admin
     * @param int $id - l'id du commentaire
     * @param int $adminI - l'id de l'admin
     * @return $result - retourne une class ou lance une exception
     */
    public function findCommentUser (int $id, int $userI) 
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND user_i = :user_i";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'id' => $id,
            'user_i' => $userI
            ]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }
}