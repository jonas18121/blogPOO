<?php

class CommentValidator{

    /** @var array $data */
    private $data;

    /** @var array $errors */
    private $errors = [];

    /** @var CommentTable $table */
    private $table;

    /** @var array  */
    //private $admin;

    /** @var int|null $commentId */
    private $commentId;

    /** @var int|null $postId */
    private $postId;


    /** hydrade et control si le titre ou le slug existe déjà
     * @param array $data - donnée venant d'un formulaire
     * @param CommentTable $table - donnée venant de la bdd
     * @param array $admin - donnée venant de la classe AdminModel
     * @param int|null $postId - l'id de l'article courant
     */
    public function __construct(array $data, CommentTable $table, ?int $commentId = null, ?int $postId = null)
    {
        $this->data       = $data;
        $this->table      = $table;
        $this->commentId  = $commentId;
        $this->postId     = $postId;

        if($this->data['user_i'] <= 0 && $this->data['admin_i'] <= 0)
        {
            $this->errors['content'][] = "vous devez vous connecter pour écrire un commentaire";
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
        if(array_key_exists('content',$this->data)){
            if(!empty($this->data['content']) && isset($this->data['content'])){
                if(!preg_match("/^[a-zA-Z0-9éèêôâïà]{1,}(.+)?$/s", $this->data['content'])){
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