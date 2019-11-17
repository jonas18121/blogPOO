<?php

class CategoryValidator{

    /** @var array $data */
    private $data;

    /** @var array $errors */
    private $errors = [];

    /** @var CategoryTable $table */
    private $table;

    /** @var int|null $id */
    private $id;

    /** hydrade et control si le titre ou le slug existe déjà
     * @param array $data - donnée venant d'un formulaire
     * @param CategoryTable $table - donnée venant de la bdd
     * @param int|null $id - l'id de l'article courant
     */
    public function __construct(array $data, CategoryTable $table, ?int $id = null)
    {
        $this->data     = $data;
        $this->table    = $table;
        $this->id       = $id;

        if($this->table->exist('slug', $this->data['slug'], $this->id) === true){
            $this->errors['slug'][] = 'Le nom du slug est déjà utilisé, écrivez un autre .';
        }
        if($this->table->exist('name', $this->data['name'], $this->id) === true){
            $this->errors['name'][] = 'Le nom du titre est déjà utilisé, écrivez un autre .';
        }
    }

    /** 
     * @return bool - returns true if $this->data is valid, otherwise it's false
     */
    public function validate() : bool
    {
        if(array_key_exists('name',$this->data)){
            if(!empty($this->data['name']) && isset($this->data['name'])){
                if(!preg_match("/^[a-zA-Z0-9]{2,}(.+)?$/", $this->data['name'])){
                    $this->errors['name'][] = "on veut pas d'espace en premier caratère dans le Titre";
                    $this->errors['name'][] = "Le Titre doit contenir minimun 2 caratères";
                }
            }
            if(empty($this->data['name'])){
                $this->errors['name'][]     = "Le champs du Titre est vide";
            }
        }
        
        if(array_key_exists('slug',$this->data)){
            if(!empty($this->data['slug']) && isset($this->data['slug'])){
                if(!preg_match("/^[a-zA-Z0-9]{2,}(.+)?$/", $this->data['slug'])){
                    $this->errors['slug'][] = "on veut pas d'espace en premier caratère dans l'URL";
                    $this->errors['slug'][] = "L'URL doit contenir minimun 2 caratères";
                }
            }
            if(empty($this->data['slug'])){
                $this->errors['slug'][]     = "Le champs de l'URL est vide";
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
