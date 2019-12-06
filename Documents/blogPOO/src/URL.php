<?php

class URL{

    /** vérifie si la page courante est bien un entier
     * @param string $name
     * @param int|null $default
     * @return int|null
     */
    public static function getInt(string $name, ?int $default = null) : ?int
    {
        if(!isset($_GET[$name])) return $default; 

        if($_GET[$name] === '0') return 0; 

        //controler que les chiffre dans $_GET['page'] soit que des entiers
        if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)){
            return 1;
        }
        return (int)$_GET[$name];
    }


    /** vérifie que ne numéro de la page courante soit positive
     * @param string $name
     * @param int|null $default
     * 
     * @return int|null
     */
    public static function getPositiveInt(string $name, ?int $default = null) : ?int
    {
        $currentPage = self::getInt($name, $default);

        if($currentPage !== null && $currentPage < 1){
            return 1;
        }
        return $currentPage;
    }
}