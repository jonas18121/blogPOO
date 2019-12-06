<?php

class PaginatedQuery{

    /** 
     * @var string $query - requête sql
     */
    private $query;

    /** 
     * @var string $queryCount - count le nombre d'element
     */
    private $queryCount;

    /** 
     * @var string $classMapping - la classe qui va etre utiliser dans le fetchAll, exmple Post::class
     */
    private $classMapping;

    /** 
     * @var PDO $pdo - connection à la base de donnée
     */
    private $pdo;
    
    /** 
     * @var int $perPage - nombre d'element par page
     */
    private $perPage;

    /** 
     * @var int $count - le nombre d'élément total qu'on a en base de donnée
     */
    private $count;

    /** 
     * @var array $items - pour eviter qu'une class soit plus d'une fois dans la même page
     */
    private $items;

    /**
     * @var Router $router - la classe router
     */
    private $router;

    public function __construct(string $query, string $queryCount, ?\PDO $pdo = null, int $perPage = 12)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        //si pdo est pas defini , on utilisera  Database::dbConnect() pour la definir
        $this->pdo = $pdo ?: Database::dbConnect();
        $this->perPage = $perPage;
    }


    
    /** afficher les articles dans leur category, avec une pagination
     * @param string $classMapping - la classe qui va etre utiliser dans le fetchAll, exmple Post::class
     * @param Router $router - la classe router
     * @return array|null $this->items - retour de la requête
     */
    public function getItems(string $classMapping, Router $router = null): ?array
    {
        if($this->items === null){
            $currentPage = $this->getCurrentPage();

            $pages = $this->getPages();

            //si la page courante est plus grand que le nombre de pages total, on lance un erreure
            if($currentPage > $pages){
                $this->router = $router;
                header('Location: ' . $this->router->url('admin_post_new') . '?NoCategoryForArticle=1');
            }

            $offset = $this->perPage * ($currentPage - 1);
            $this->items = $this->pdo->query(
                $this->query .
                " LIMIT {$this->perPage} OFFSET {$offset}"
            )
            ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }


    /** afficher les articles dans leur category, avec une pagination
     * @param string $classMapping - la classe qui va etre utiliser dans le fetchAll, exmple Post::class
     * @param int $userI
     * @param Router $router - la classe router
     * @return array|null $this->items - retour de la requête
     */
    public function getItemsAdmin(string $classMapping, int $userI, Router $router): ?array
    {
        if($this->items === null){
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPagesAdmin($userI);
            if($currentPage > $pages){
                $this->router = $router;
                header('Location: ' . $this->router->url('admin_post_new') . '?NoCreatedArticle=1');
            }

            //calcul de l'offset pour pouvoir afficher de nouvelle article dans chaques pages courante
            $offset = $this->perPage * ($currentPage - 1);
            $this->items = $this->pdo->prepare(
                $this->query .
                " LIMIT {$this->perPage} OFFSET {$offset}"
            );
            
            $this->items->execute([
                'admin_i' => $userI
            ]); 
            return $this->items->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }


    
    /** généré les liens pour le bouton precédent
     * @param string $link - la partie php du lien 
     * @return string|null - le lien complèt html et php du boutton page précédent
     */
    public function previousLink(string $link) : ?string
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
            <button class="pagination"><a href="{$link}">&laquo; page précédente</a></button>
HTML;
    }

    /** généré les liens pour le bouton precédent pour l'utilisateur
     * @param string $link - la partie php du lien 
     * @return string|null - le lien complèt html et php du boutton page précédent
     */
    public function previousLinkUser(string $link) : ?string
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
            <button class="pagination"><a href="{$link}">&laquo; page précédente</a></button>
HTML;
    }


    
    /** généré les liens pour le bouton suivant
     * @param string $link - la partie php du lien  
     * @return string|null - le lien complèt html et php du boutton page suivant
     */
    public function nextLink(string $link) : ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
            <button class="pagination"><a href="{$link}">page suivante &raquo;</a></button>
HTML;
    }

    /** généré les liens pour le bouton suivant pour l'utilisateur
     * @param string $link - la partie php du lien 
     * @param int $userI 
     * @return string|null - le lien complèt html et php du boutton page suivant
     */
    public function nextLinkAdmin(string $link, int $adminI) : ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPagesAdmin($adminI);
        if($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
            <button class="pagination"><a href="{$link}">page suivante &raquo;</a></button>
HTML;
    }


    /**
     * @return int - on récupère la page courante
     */
    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }

    /** //on recupéré le nombre de d'article par category pour faire une pagination
     * @return int - le nombre de page total
     */
    private function getPages() : int
    {
        if($this->count === null){
            $this->count = (int)$this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }
        return (int)ceil($this->count / $this->perPage);
    }

    /** //on recupéré le nombre de d'article par category pour faire une pagination
     * @param int $userI
     * @return int - compte les pages pour l'utilisateur connecter
     */
    private function getPagesAdmin(int $adminI) : int
    {
        if($this->count === null){
            $this->count = $this->pdo->prepare($this->queryCount);
            $this->count->execute(['admin_i' => $adminI]);
            $this->count = $this->count->fetch();
        }
        return (int)ceil((int)$this->count['COUNT(id)'] / $this->perPage);
    }
}
