<?php

class UserValidator{

    /** @var array $data */
    private $data;

    /** @var array $errors */
    private $errors = [];

    /** @var UserTable $table */
    private $table;

    /** @var int|null $id */
    private $id;

    /** hydrade 
     * @param array $data - donnée venant d'un formulaire
     * @param UserTable $table - donnée venant de la bdd
     * @param int|null $id - l'id de l'utiliateur courant
     */
    public function __construct(array $data, UserTable $table, ?int $id = null)
    {
        $this->data     = $data;
        $this->table    = $table;
        $this->id       = $id;
    }

    /** control
     * @return bool - returns true if $this->data is valid, otherwise it's false
     */
    public function validate() : bool
    {
        if(array_key_exists('username',$this->data)){
            if(!empty($this->data['username']) && isset($this->data['username'])){
                if(!preg_match("/^[a-zA-Z0-9]{2,}(.+)?$/", $this->data['username'])){
                    $this->errors['username'][] = "on veut pas d'espace en premier caratère dans ce champ";
                    $this->errors['username'][] = "L'utilisateur doit contenir minimun 2 caratères";
                }
            }
            if(empty($this->data['username'])){
                $this->errors['username'][]     = "Le champs de l'utilisateur est vide";
            }
        }

        if(array_key_exists('email',$this->data)){
            if(!empty($this->data['email']) && isset($this->data['email'])){
                if(!preg_match("/^[a-zA-Z][a-zA-Z0-9._-]{1,19}@[a-z]{4,7}\.[a-z]{2,3}$/", $this->data['email'])){
                    $this->errors['email'][] = "on veut pas d'espace en premier caratère dans ce champ";
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
                    $this->errors['password'][] = "on veut pas d'espace en premier caratère dans le mot de passe";
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