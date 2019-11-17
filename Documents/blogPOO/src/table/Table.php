<?php

abstract class Table{

    /** @var PDO */
    protected $pdo;

    /** @var string|null $table - nom de la table */
    protected $table = null;
    
    /** $class - nom de la classe */
    protected $class = null;
    

    public function __construct(PDO $pdo = null)
    {
        if($this->table === null){
            throw new Exception("La class " . get_class($this) . " n'a pas de propriété \$table");
        }
        if($this->class === null){
            throw new Exception("La class " . get_class($this) . " n'a pas de propriété \$classe");
        }
        if($this->pdo === null){
            $this->pdo = Database::dbConnect();
        }else{
            $this->pdo = $pdo;
        }
    }

    /** supprimer un élément depuis son id
     * @param int $id
     * @return void|throw
     */
    public function delete (int $id) : void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $ok = $query->execute(['id' => $id]);
        if($ok === false){
            throw new Exception("Impossible de supprimer l'enregistrement {$id} dans la table {$this->table}. ");
        }
    }

    /** selectionne tous ce qu'il y a dans une table selectionner à partir de son id
     * @param int $id
     * @return $result - retourne une class ou lance une exception
     */
    public function findOne (int $id) 
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $query = $this->pdo->prepare($sql);
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            return $errors = 'la modification est incorrecte';
        }
        return $result;
    }

    /** vérifie si une valeur existe dans une table
     * @param string $exist - nom du champ qu'on veut analyser
     * @param mixed $value - valeur que l'utilisateur à entrer via un formulaire dans le champ en question
     * @param int $except - l'id de l'article courant
     * @return bool
     */
    public function exist(string $exist, $value, ?int $except = null) : bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $exist = ?";
        $params = [$value];
        if($except !== null){
            $sql .= " AND id != ?";
            $params[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
    }

    /**
     * @return array - selectionne tous ce qu'il y a dans une table en ordre déccroissant
     */
    public function all() : array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

    /** créer un élément
     * @param array $data - données venant du formulaire
     * @return int
     */
    public function create(array $data) : int
    {
        $sqlFields =[];
        // les paramètre seront dynamiques
        foreach($data as $key => $value){
            $sqlFields[] = "$key = :$key";// exemple 'name' = ':name'
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));
        $ok = $query->execute($data);
        if($ok === false){
            throw new Exception("Impossible de créer l'article dans la table {$this->table}. ");
        }
        return (int)$this->pdo->lastInsertId();
    }


    /** modifier un élément
     * @param array $data - données venant du formulaire
     * @param int $id 
     * @return void
     */
    public function update(array $data, int $id) : void
    {
        $sqlFields = [];
        // les paramètre seront dynamiques
        foreach($data as $key => $value){
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id");
        $ok = $query->execute(array_merge($data, ['id' => $id]));
        if($ok === false){
            throw new Exception("Impossible de modifier l'élément {$data->getId()} dans la table {$this->table}. ");
        }
    }
}