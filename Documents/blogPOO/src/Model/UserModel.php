<?php

class UserModel{

    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var string $email */
    private $email;

    /** @var string $password */
    private $password;

    /** @var string $password2 */
    private $password2;


            /* ----- GETTER ----- */

    /** afficher l'id
     * @param void
     * @return int|null 
    */        
    public function getId() : ?int
    {
        return $this->id;
    }

    /** afficher le nom de l'utilisateur
     * @param void
     * @return string|null 
    */        
    public function getName() : ?string
    {
        return $this->name;
    }

    /** afficher le mail de l'utilisateur
     * @param void
     * @return string|null 
    */        
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /** afficher le mot de passe de l'utilisateur
     * @param void
     * @return string|null 
    */        
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /** afficher le 2èmes mot de passe de l'utilisateur
     * @param void
     * @return string|null 
    */        
    public function getPassword2() : ?string
    {
        return $this->password2;
    }

            /* ----- SETTER ----- */   
       
    /** définir l'id
     * @param int $id
     * @return self
    */        
    public function setid(int $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /** définir le nom de l'utilisateur
     * @param string $username
     * @return self
    */        
    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /** définir le nom de l'utilisateur
     * @param string $username
     * @return self
    */        
    public function setEmail(string $email) : self
    {
        /* on teste si le mail existe et est valide , s'il est différent de false , on continue.
         */
        if(isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false){
            
            $table = new UserTable(); 
            $email = $table->findEmail($email);
            $this->email = $email;
            return $this;
        }
        throw new Exception(("votre mail n'est pas valide.")); 
    }

    /** définir le mot de passe de l'utilisateur
     * @param string $password
     * @return self
    */        
    public function setPassword(string $password) : self
    {
        /* si le mail n'existe pas déjà dans la bdd lors de l'inscription, c'est bon , 
        on peut hashé le mot de passe */
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $passwordHashed;
        return $this;
    }

    /** définir le 2èmes mot de passe de l'utilisateur
     * @param string $password
     * @return self
    */        
    public function setPassword2(string $password2) : self
    {
        /* si le mail n'existe pas déjà dans la bdd lors de l'inscription, c'est bon , 
        on peut hashé le mot de passe */
        $passwordHashed  = password_hash($password2, PASSWORD_DEFAULT);
        $this->password2 = $passwordHashed;
        return $this;
    }

    /** on verifie si la session user existe
     *  @return bool
    */
    public function isAuthenticatedUser() : bool
    {
        if(array_key_exists('user', $_SESSION)) {
            if (!empty($_SESSION['user']) && isset($_SESSION['user'])) {
                return true;
            }
        }
        return false;
    }
}