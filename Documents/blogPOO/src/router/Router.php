<?php

class Router {

    /** @var string */
    private $viewPath;

    /** @var AltoRouter */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    /** une route en GET
     * @param string $url - $this->viewPath
     * @param string $view - charger la vue qu'on veut
     * @param string|null $name - nom de la route
     * 
     * @return self $this - class Router
     */
    public function get(string $url, string $view, ?string $name = null) : self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    /** une route en POST
     * @param string $url - $this->viewPath
     * @param string $view - charger la vue qu'on veut
     * @param string|null $name - nom de la route
     * 
     * @return self $this = class Router
     */
    public function post(string $url, string $view, ?string $name = null) : self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    /**  une route qui peut être envoyer en GET mais aussi en POST
     * @param string $url - $this->viewPath
     * @param string $view - charger la vue qu'on veut
     * @param string|null $name - nom de la route
     * 
     * @return self $this - class Router
     */
    public function match(string $url, string $view, ?string $name = null) : self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    /** fera appel à AltoRouter pour générer des urls
     * @param string $name - nom de la route
     * @param array $params - les paramètres
     * @return string
     */
    public function url(string $name, array $params = []) : string
    {
        return $this->router->generate($name, $params);
    }

    /** Acceder au bonne page, que l'utilisateur a demander
     * @param void
     * @return self
     */
    public function run() : self
    {
        $match = $this->router->match();
        $router = $this;//pour accéder à toutes les variables, lorsqu'on utilise url()
        $view = $match['target'];
        ($view === null ) ? header('Location: ' . $router->url('home')) : '' ; 
        $params = $match['params'];

        try{
            ob_start();
            require_once $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';// on charge la vue
            $content = ob_get_clean();
            require_once $this->viewPath . DIRECTORY_SEPARATOR . 'element/layout.php';//on charge le layout
        } 
        catch(SecurityException $e){
            header('Location: ' . $this->url('login_user') . '?security=1');
            exit();
        }
        return $this;
    }
}