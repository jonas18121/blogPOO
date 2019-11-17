<?php

class NotFoundException extends Exception{

    /** retoune une exception si le id le correspond pas à la table concerné
     * @param string $table
     * @param int|string $param
     * @return throw
     */
    public function __construct(string $table, $param)
    {
        $this->message = "Aucun enregistrement ne correspond à : {$param} dans la table {$table}.";
    }
}