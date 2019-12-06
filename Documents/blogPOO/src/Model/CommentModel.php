<?php

class CommentModel{

    /** @var int */
    private $id;

    /** @var string */
    private $content;

    /** @var DateTime */
    private $created_at;

    /** @var int */
    private $admin_i;

    /** @var int */
    private $user_i;

    /** @var int */
    private $id_post;

    /** @var string */
    private $slug_post;

    /** @var string */
    private $author;


            /*------- GETTER -----------*/

    /** affiche la date sous forme DateTime
     * @param void
     * @return DateTime
     */
    public function getCreatedAt() : DateTime 
    {
        return new \DateTime($this->created_at);
    }
    
    /** afficher le commentaire
     * @param void
     * @return string|null
     */
    public function getContent() : ?string
    {
        return nl2br($this->content);
    }

    /** afficher l'id
     * @param void
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /** afficher le nom de l'auteur
     * @param void
     * @return string|null
     */
    public function getAuthor() : ?string
    {
        return $this->author;
    }

    /** afficher l'admin_i
     * @param void
     * @return int|null
     */
    public function getAdminI() : ?int
    {
        return $this->admin_i;
    }

    /** afficher l'user_i
     * @param void
     * @return int|null
     */
    public function getUserI() : ?int
    {
        return $this->user_i;
    }

    /** afficher l'id du post
     * @param void
     * @return int|null
     */
    public function getIdPost() : ?int
    {
        return $this->id_post;
    }

    /** afficher le slug du post
     * @param void
     * @return int|null
     */
    public function getSlugPost() : ?string
    {
        return $this->slug_post;
    }

    
            /*------- SETTER -----------*/

    /** setter pour definir l'id
     * @param int $id
     * @return self
     */
    public function setId(int $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /** setter pour definir l'id de l'admin qui va avec le commentaire
     * @param $id
     * @return self
     */
    public function setAdminI($adminI) : self
    {
        $this->admin_i = (int)$adminI;
        return $this;
    }


    /** setter pour definir le nom de l'auteur qui va avec le commentaire
     * @param string $author
     * @return self
     */
    public function setAuthor(string $author) : self
    {
        $this->author = $author;
        return $this;
    }

    /** setter pour definir l'id de l'utilisateur qui va avec le commentaire
     * @param $id
     * @return self
     */
    public function setUserI($userI) : self
    {
        $this->user_i = (int)$userI;
        return $this;
    }

    /** setter pour definir le date
     * @param string $date
     * @return self
     */
    public function setCreatedAt(string $date) : self
    {
        $this->created_at = $date;
        return $this;
    }

    /** setter pour definir le contenu
     * @param string $name
     * @return self
     */
    public function setContent(string $content) : self
    {
        $this->content = $content;
        return $this;
    }

    /** setter pour definir l'id du post
     * @param string $name
     * @return self
     */
    public function setIdPost(int $id_post) : self
    {
        $this->id_post = $id_post;
        return $this;
    }

    /** setter pour definir le slug du post
     * @param string $name
     * @return self
     */
    public function setSlugPost(string $slug_post) : self
    {
        $this->slug_post = $slug_post;
        return $this;
    }
}