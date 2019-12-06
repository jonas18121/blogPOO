<?php

class AdminValidator{

    /** @var array $data */
    private $data;

    /** @var array $errors */
    private $errors = [];

    /** @var AdminTable $table */
    private $table;

    /** @var int|null $id */
    private $id;

    /** hydrade 
     * @param array $data - donnée venant d'un formulaire
     * @param AdminTable $table - donnée venant de la bdd
     * @param int|null $id - l'id de l'administrateur courant
     */
    public function __construct(array $data, AdminTable $table, ?int $id = null)
    {
        $this->data     = $data;
        $this->table    = $table;
        $this->id       = $id;

        /* on teste si le mail existe et est valide , s'il est différent de false , on continue.*/
        if(isset($this->data['email']) && filter_var($this->data['email'], FILTER_VALIDATE_EMAIL) === false)
        {
            $this->errors['email'][] = 'Ce mail n\'est pas valide, écrivez un autre .';
        }

        if(count($this->data) > 2){
            if($table->exist('email', $this->data['email']) === true){
                $this->errors['email'][] = 'Ce mail est déjà utilisé, écrivez un autre .';
            }
        }

        if(isset($this->data['password2'])){
            if($this->data['password'] !== $this->data['password2']){
                $this->errors['password'][] = 'Les deux mots de passe sont différents .';
                $this->errors['password2'][] = 'Les deux mots de passe sont différents .';
            }
        }
        
    }

    /** control
     * @return bool - returns true if $this->data is valid, otherwise it's false
     */
    public function validate() : bool
    {
        if(array_key_exists('name',$this->data)){
            if(!empty($this->data['name']) && isset($this->data['name'])){
                if(!preg_match("/^[a-zA-Z0-9éèêôâïà]{2,}(.+)?$/", $this->data['name'])){
                    //$this->errors['name'][] = "Pas d'espace en premier caratère dans ce champ";
                    $this->errors['name'][] = "L'utilisateur doit contenir minimun 2 caratères";
                }
            }
            if(empty($this->data['name'])){
                $this->errors['name'][]     = "Le champs de votre de nom est vide";
            }
        }

        if(array_key_exists('email',$this->data)){
            if(!empty($this->data['email']) && isset($this->data['email'])){
                if(!preg_match("/^[a-zA-Z][a-zA-Z0-9._-]{1,19}@[a-z]{4,7}\.[a-z]{2,3}$/", $this->data['email'])){
                    //$this->errors['email'][] = "on veut pas d'espace en premier caratère dans ce champ";
                    $this->errors['email'][] = "vous devez écrire un mail correcte, exemple : ok@laposte.fr";
                }
            }
            if(empty($this->data['email'])){
                $this->errors['email'][]     = "Le champs du mail est vide";
            }
        }
        
        if(array_key_exists('password',$this->data)){
            if(!empty($this->data['password']) && isset($this->data['password'])){
                if(!preg_match("/^[a-zA-Z0-9]{2,}(.+)?$/", $this->data['password'])){
                    //$this->errors['password'][] = "on veut pas d'espace en premier caratère dans le mot de passe";
                    $this->errors['password'][] = "Le mot de passe doit contenir minimun 2 caratères";
                }
            }
            if(empty($this->data['password'])){
                $this->errors['password'][]     = "Le champs du mot de passe est vide";
            }
        }
        
        if(empty($this->errors)){
            return true;
        }
        return false;
    }

    /** returns errors
     * @return array
     */
    public function errors() : array
    {
        return $this->errors;
    }
}