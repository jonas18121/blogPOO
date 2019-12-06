<?php

class PostModel{

    /** @var int */
    private $id;

    /** @var string */
    private $slug;

    /** @var string */
    private $name;

    /** @var string */
    private $content;

    /** @var DateTime */
    private $created_at;

    /** @var int */
    private $category_id;

    /** @var int */
    private $admin_i;

    /** @var array $categories - liste des category*/
    private $categories = []; 

    /** @var Router $router */
    private $router;


    public function __construct($router = null)
    {
        $this->router = $router;
    }


            /*------- GETTER -----------*/

    /** getter afficher le nom
     * @param void
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /** getter afficher le contenu
     * @param void 
     * @return string|null
     */
    public function getFormattedContent() : ?string
    {
        return nl2br(utf8_encode($this->content));
    }

    /** affiche la date sous forme DateTime
     * @param void
     * @return DateTime
     */
    public function getCreatedAt() : DateTime 
    {
        return new \DateTime($this->created_at);
    }

    /** afficher le contenu
     * @param void
     * @return string|null
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /** afficher le slug
     * @param void
     * @return string|null
     */
    public function getSlug() : ?string
    {
        return $this->slug;
    }

    /** afficher l'id
     * @param void
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /** afficher l'id de la category
     * @param void
     * @return int|null
     */
    public function getCategoryId() : ?int
    {
        return $this->category_id;
    }

    /** getter afficher l'id de l'utilisateur
     * @param void
     * @return int|null
     */
    public function getAdminI() : ?int
    {
        return $this->admin_i;
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

    /** setter pour definir l'id de la category qui va avec le poste
     * @param $id
     * @return self
     */
    public function setCategoryId($categoryId) : self
    {
        $this->category_id = (int)$categoryId;
        return $this;
    }

    /** setter pour definir l'id  de l'admin qui va avec le poste
     * @param $id
     * @return self
     */
    public function setAdminI($adminI) : self
    {
        $this->admin_i = (int)$adminI;
        return $this;
    }


    /** setter pour definir le nom
     * @param string $name
     * @return self
     */
    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /** setter pour definir le slug
     * @param string $slug
     * @return self
     */
    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;
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

    /** setter pour definir le date
     * @param string $date
     * @return self
     */
    public function setCreatedAt(string $date) : self
    {
        $this->created_at = $date;
        return $this;
    }
    
            /*------- METHODE OTHER -----------*/

    /** traiter le texte et Afficher un extrait de mon article
     * @param void
     * @return string|null
     */
    public function getExcerpt() : ?string
    {
        if($this->content === null){
            return null;
        }
        // class Text
        return Text::excerpt($this->content,110);
    } 
}