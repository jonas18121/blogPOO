<?php

class CategoryModel{

    /** @var int $id */
    private $id;

    /** @var string $slug */
    private $slug;

    /** @var string $name */
    private $name;


        /*------- GETTER -----------*/

    /** getter afficher l'id
     *  @param void
     * @return int|null
    */    
    public function getId(): ?int
    {
        return $this->id;
    }

    /** getter afficher le slug
     *  @param void
     * @return string|null
    */ 
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /** getter afficher le name
     *  @param void
     * @return string|null
    */ 
    public function getName(): ?string
    {
        return htmlentities(utf8_encode($this->name));
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

    /** setter pour definir le slug
     * @param string $slug
     * @return self
     */
    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;
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
}