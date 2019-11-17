<?php 

class CategoryTable extends Table{

    /** @var string $table - nom de la table */
    protected $table = 'category';

    /** @var CategoryModel $class - la classe CategoryModel  */
    protected $class = CategoryModel::class;

    /** selectionne la category qui va avec un article précis
     * @param int $id
     * @return array|throw
     */
    public function oneCategoryByPost(int $id) : array
    {
        // selectionne la category qui va avec le poste
        $query = $this->pdo->prepare(
            "SELECT category.id, category.slug, category.name 
            FROM post 
            INNER JOIN {$this->table} 
            ON post.category_id = category.id 
            WHERE post.id = :id "
        );
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        /** @var CatagoryModel */
        $oneCategories = $query->fetchAll();
        if($oneCategories === false){
            throw new NotFoundException('post', $id);
        }
        return $oneCategories;
    }

    /** selectionne une category 
     * @param int $id
     * @return array|throw
     */
    public function oneCategory(int $id) : array
    {
        $query = $this->pdo->prepare(
            "SELECT * FROM {$this->table} where id = :id"
        );
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        /** @var CatagoryModel */
        $oneCategories = $query->fetchAll();
        if(empty($oneCategories) || $oneCategories === false){
            return $error = [0];//'La modification est incorrecte'
        }
        return $oneCategories;
    }
}