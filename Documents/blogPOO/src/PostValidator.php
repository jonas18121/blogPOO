<?php

class PostValidator{

    /** @var array $data */
    private $data;

    /** @var array $errors */
    private $errors = [];

    /** @var PostTable $table */
    private $table;

    /** @var array CategoryModel */
    private $category;

    /** @var int|null $postId */
    private $postId;

    /** hydrade et control si le titre ou le slug existe déjà
     * @param array $data - donnée venant d'un formulaire
     * @param PostTable $table - donnée venant de la bdd
     * @param array $category - donnée venant de la classe CategoryModel
     * @param int|null $postId - l'id de l'article courant
     */
    public function __construct(array $data, PostTable $table, array $category , ?int $postId = null)
    {
        $this->data     = $data;
        $this->table    = $table;
        $this->category = $category;
        $this->postId   = $postId;

        if($this->table->exist('slug', $this->data['slug'], $this->postId) === true){
            $this->errors['slug'][] = 'Le nom du slug est déjà utilisé, écrivez un autre .';
        }
        if($this->table->exist('name', $this->data['name'], $this->postId) === true){
            $this->errors['name'][] = 'Le nom du titre est déjà utilisé, écrivez un autre .';
        }
    }

    /** vérifier si le format de la date est valide ou pas
     * @param string $date - date venant du formulaire
     * @param string $format - le format de date que l'on veut
     * @return bool - true si valide ou false si invalide
     */
    public function validateDate(string $date, string $format = 'd-m-Y H:i:s') : bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /** 
     * @return bool - returns true if $this->data is valid, otherwise it's false
     */
    public function validate() : bool
    {

        if(array_key_exists('name',$this->data)){
            if(!empty($this->data['name']) && isset($this->data['name'])){
                if(!preg_match("/^[a-zA-Z0-9éèêôâïà]{1,}(.+)?$/", $this->data['name'])){
                    //$this->errors['name'][] = "on veut pas d'espace en premier caratère dans le Titre";
                    $this->errors['name'][] = "Le Titre doit contenir minimun 1 caratère normal";
                }
            }
            if(empty($this->data['name'])){
                $this->errors['name'][]     = "Le champs du Titre est vide";
            }
        }
        
        if(array_key_exists('slug',$this->data)){
            if(!empty($this->data['slug']) && isset($this->data['slug'])){
                if(!preg_match("/^[a-zA-Z0-9éèêôâïà]{1,}(.+)?$/", $this->data['slug'])){
                    //$this->errors['slug'][] = "on veut pas d'espace en premier caratère dans l'URL";
                    $this->errors['slug'][] = "L'URL doit contenir minimun 1 caratère normal";
                }
            }
            if(empty($this->data['slug'])){
                $this->errors['slug'][]     = "Le champs de l'URL est vide";
            }
        }

        if(array_key_exists('content',$this->data)){
            if(!empty($this->data['content']) && isset($this->data['content'])){
                if(!preg_match("/^[a-zA-Z0-9éèêôâïà]{1,}(.+)?$/s", $this->data['content'])){
                    //$this->errors['content'][] = "on veut pas d'espace en premier caratère dans le contenu";
                    $this->errors['content'][] = "Le contenu doit contenir minimun 1 caratère normal";
                }
            }
            if(empty($this->data['content'])){
                $this->errors['content'][]     = "Le champs du Contenu est vide";
            }
        }

        if(array_key_exists('created_at',$this->data)){
            if(!empty($this->data['created_at']) && isset($this->data['created_at'])){
                if($this->validateDate($this->data['created_at']) === false ){
                    $this->errors['created_at'][] = "Le format de la date est incorrecte ";
                    $this->errors['created_at'][] = "Veuillez écrire une date dans ce format: DD-MM-YYYY HH:MM:SS ";
                }
            }
            if(empty($this->data['created_at'])){
                $this->errors['created_at'][]     = "Le champs du Contenu est vide";
            }
        }

        if(array_key_exists('category_id',$this->data)){
            if(!empty($this->data['category_id']) && isset($this->data['category_id'])){
                if($this->category[0] === 0){
                    $this->errors['category_id'][] = "La modification est incorrecte";
                }
                elseif($this->category[0]->getId() !== (int)$this->data['category_id']){
                    $this->errors['category_id'][] = "La modification est incorrecte";
                }
            }
            if(empty($this->data['category_id'])){
                $this->errors['category_id'][]  = "Le champs du Contenu est vide";
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