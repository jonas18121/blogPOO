<?php

class Auth{

    /** vérifier que l'utilisateur est bien connecté 
     * @return void
     */
    public static function check() : void
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['admin'])){
            throw new SecurityException();
        }
    }
}