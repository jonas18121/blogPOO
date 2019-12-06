<?php

class PostTable extends Table {

    /** @var string $table - nom de la table */
    protected $table = 'post';

    /** @var PostModel $class - la classe PostModel  */
    protected $class = PostModel::class;
    
    /** @var Router $router - object Router */
    protected $router;

    public function __construct($pdo = null, $router = null){
        parent::__construct();
        if(isset($router) && $router !== null){
            $this->router = $router;
        }
    } 

    /** retoune la selection de tous les articles + l'objet de la class PaginatedQuery()
     * @return array - renvois [$posts, $paginatedQuery]
     */
    public function findPaginated() : array
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * 
            FROM {$this->table} 
            ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo
        );
        /** @var PostModel[] */
        $posts = $paginatedQuery->getItems($this->class, $this->router);

        return [$posts, $paginatedQuery]; 
    } 



    /** select une liste d'article pour chaque category
     * @param int $categoryId
     * @return array - renvois [$posts, $paginatedQuery]
     */
    public function findPaginatedForCategory(int $categoryId) : array
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT post.*
            FROM {$this->table} 
            INNER JOIN category 
            ON category.id = post.category_id
            WHERE post.category_id = {$categoryId}
            ORDER BY created_at DESC", //requête sql
            "SELECT COUNT(category_id) FROM {$this->table} WHERE category_id = {$categoryId}",// compteur
            $this->pdo,
            3
        );
        /** @var PostModel[] */
        $posts = $paginatedQuery->getItems($this->class, $this->router);

        //on renvoie 2 variables dans un tableau
        return [$posts, $paginatedQuery]; 
    }

    /** retoune la selection de tous les articles de l'utilisateur + l'objet de la class PaginatedQuery()
     * @param int $userI
     * @return array - renvois [$posts, $paginatedQuery]
     */
    public function findPaginatedAdmin(int $adminI) : array
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * 
            FROM {$this->table} 
            WHERE admin_i = :admin_i
            ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->table} WHERE admin_i = :admin_i",
            $this->pdo
        );
        /** @var PostModel[] */
        $posts = $paginatedQuery->getItemsAdmin($this->class,$adminI, $this->router);

        return [$posts, $paginatedQuery]; 
    }

    /** selectionne seulement le admin_i de la table post selectionner à partir de admin_i
     * @param int $userI
     * @return array|string $result - retourne une class ou lance une exception
     */
    public function findOnlyUserI (int $adminI) 
    {
        $sql = "SELECT admin_i FROM {$this->table} WHERE admin_i = :admin_i";
        $query = $this->pdo->prepare($sql);
        $query->execute(['admin_i' => $adminI]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            return $errors = 'pas de admin_i trouver';
        }
        return $result;
    }

    /** Effacer tous les articles d'un utilisateur précis
     * @param int $userI
     * @return void|throw
     */
    public function deleteAllPostAdmin(int $adminI) : void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE admin_i = :admin_i");
        $ok = $query->execute(['admin_i' => $adminI]);
        if($ok === false){
            throw new Exception("Impossible de supprimer les enregistrement {$adminI} dans la table {$this->table}. ");
        }
    }

    /** selectionne un article à partir de son id et l'id de l'admin
     * @param int $id
     * @param int $adminI
     * @return $result - retourne une class ou lance une exception
     */
    public function findPostAdmin (int $id, int $adminI) 
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

    /** selectionne tous les articles à partir de l'id de l'admin
     * @param int $adminI
     * @return $result - retourne une class ou lance une exception
     */
    public function findAllPostAdmin (int $adminI) 
    {
        $sql = "SELECT * FROM {$this->table} WHERE admin_i = :admin_i";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'admin_i' => $adminI
            ]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();
        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }
}