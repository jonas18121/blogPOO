<?php

class UserTable extends Table {

    /** @var string $table - nom de la table */
    protected $table = 'user';

    /** @var UserModel $class - la classe UserModel  */
    protected $class = UserModel::class;

    /** selectionne tous ce qu'il y a dans une table selectionner à partir de son $email lors de la connexion
     * @param string $email - l'email que l'utilisateur a entrer dans le formulaire
     * @return $result - retourne une class ou lance une exception
     */
    public function login(string $email)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email ");
        $query->execute(['email' => $email]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            throw new NotFoundException("{$this->table}", $email);
        }
        return $result;
    }

    /** inscription
     * @param array $data - donnée entrer par l'utilisateur
     * @return void|throw 
     */
    public function register(array $data) : void
    {
            $sqlFields = [];
            // les paramètres seront dynamiques
            foreach($data as $key => $value){
                $sqlFields[] = "$key = :$key";// exemple 'name' = ':name'
            } 
            $sql = "INSERT INTO {$this->table} SET " . implode(', ', $sqlFields);
            $user = $this->pdo->prepare($sql);
            $user = $user->execute($data);
            if($user === false){
                throw new Exception("Impossible de créer un compte dans la table {$this->table}. ");
            }
    }

    /** l'utilisateur supprime son compte 
     * @param int $id - l'id de l'utilisateur
     * @return void
    */
    public function deleteUser(int $id) : void 
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id ";
        $deleteUser = $this->pdo->prepare($sql);
        $deleteUser->execute([':id' => $id]);
    }

    /** selectionne et controle si l'id de l'utilisateur existe déjà dans la bdd
     * @param int $id - l'id de l'utilisateur
     * @return int|throw $userI - retoune l'id ou lance une erreur si l'id n'existe pas
     */
    public function findIdAdmin(int $id) : int
    {
        //si le mail est dans la table user , on selectionne tous le contenue de cette table
        $sql = "SELECT id FROM {$this->table} WHERE id = :id ";
        $userI = $this->pdo->prepare($sql);
        $userI->execute([
            ':id' => $id
        ]);
        $userI = $userI->fetch();
        if($userI === null || $userI === false){
            throw new Exception(("Cette identifiant n'existe pas"));
        }
        return (int)$userI['id'];
    }

    /** controle si le mail existe déjà dans la bdd
     * @param string $email - l'email que l'utilisateur a entrer dans le formulaire
     * @return string|throw $email - retoune le mail ou lance une erreur s'il existe déjà
     */
    public function findEmail(string $email) : string
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email ";
        $userExist = $this->pdo->prepare($sql);
        $userExist->execute([
            ':email' => $email
        ]);
        $userExist = $userExist->fetchAll();
        if($userExist){
            throw new Exception(("Un utilisateur existe déjà avec cet email."));
        }
        return $email;
    }
} 