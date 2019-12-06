<?php


class Form{

    /**  instance d'une classe */
    private $data;

    /** @var array $errors - tableau des erreures */
    private $errors; 

    public function __construct($data, array $errors)
    {
      $this->data   =  $data ;
      $this->errors = $errors;
    }

    /** génère des inputs 
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @param string $label - le nom qui sera affiché a l'utilisateur
     * @return string - le champs de l'input
    */
    public function inpute(string $key, string $label = null) : string
    {
        $value = $this->getValue($key);
        //$type = (($key === 'password') ? 'password' : (($key === 'admin_i') ? 'hidden' : 'text'));
        if($key === 'password' || $key === 'password2'){
            $type = 'password';
        }
        elseif($key === 'admin_i' || $key === 'user_i' || $key === 'id_post' || $key === 'slug_post' || $key === 'author'){
            $type = 'hidden';
        }
        else{
            $type = 'text';
        }

        return "
        <div>
            <label for='{$key}'>" . utf8_encode(htmlentities($label)) . "</label>
            <input type='{$type}' id='{$key}' name='{$key}' class='{$this->getInputClass($key)}' value='". utf8_encode(htmlentities($value)) ."' required>
            {$this->getErrorFeedBack($key)}
        </div>";
    }


    /** génère des textareas 
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @param string $label - le nom qui sera affiché a l'utilisateur
     * @return string - le champs du textarea
    */
    public function textarea (string $key, string $label) : string
    {
        $value = $this->getValue($key);
        $value =  nl2br($value);
        return "
        <div>
            <label for='{$key}'>" . utf8_encode(htmlentities($label)) . "</label>
            <textarea type='text' id='{$key}' name='{$key}' class='{$this->getInputClass($key)}' autocapitalize='sentences' required>" . utf8_encode(htmlentities($value)) . "</textarea>
            {$this->getErrorFeedBack($key)}
        </div>";
    }

    /** génère la liste de selection
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @param string $label - le nom qui sera affiché a l'utilisateur
     * @param array $category - liste de categories disponible
     * @return string - le champs de selection
    */
    public function select(string $key, string $label, array $category = null) : string
    {
        $value = $this->getValue($key);
        $select = "
            <div>
                <label for='{$key}'>" . utf8_encode(htmlentities($label)) . "</label>
                <select name='{$key}' class='{$this->getInputClass($key)}' required> "; 
                    $select .= "<option value=''>--- Sélectionnez une catégorie ---</option>";
                    foreach($category as $value){
                        $id = (int)$value->getId();
                        $select .= "<option value='{$id}'>{$value->getName()}</option>";
                    };
                $select .= "</select>
                    {$this->getErrorFeedBack($key)}
            </div>";

        return $select;
    }



    /** renverra la valeur associer au name du input 
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @return string|null
    */
    private function getValue (string $key) : ?string
    {
        if(is_array($this->data)){
            return $this->data[$key] ?? null;
        }
        //rendre le nom de la methode dynamique
        $method = 'get'. str_replace(' ', '', ucwords(str_replace('_', ' ',$key)));

        $value = $this->data->$method();//exemple getName()
        // traiter l'affichate de date
        if($value instanceof DateTimeInterface) {
            return $value->format('d-m-Y H:i:s');
        }
        return $value;
    }

    /** vérifie si la variable $this->errors existe pour coloré l'input en rouge
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @return string
     */
    private function getInputClass(string $key) : string
    {
        $inputColor = '';
        if(isset($this->errors[$key])){
            $inputColor = 'invalid';
        }
        return $inputColor;
    }

     /** vérifie si la variable $this->errors existe pour affiche un message en rouge 
     * @param string $key - le nom qui sera la valeur des attributs des inputs
     * @return string
     */
    private function getErrorFeedBack(string $key) : string
    {
        $feedBack = '';

        if(isset($this->errors[$key])){
            if(is_array($this->errors[$key])){
                $errors = implode('<br>', $this->errors[$key]);
            } else{
                $errors = $this->errors[$key];
            }
            $feedBack = "<div class='errors'>". $errors . '</div>';
            return $feedBack;
        }
        return '';
    }
}