<?php

class Text{

    /** Afficher un extrait de mon article
     * @param string $content - le contenu de l'article
     * @param int $limit - c'est à cette limite qu'on va devoir couper le texte 
     * @return string - chaine de caractère avec 60 caractères maximun
     */
    public static function excerpt(string $content, int $limit = 60) : string
    {
        if(mb_strlen($content) <= $limit){
            return $content;
        }
        $lastSpace = mb_strpos($content, ' ', $limit);// s'arrête au 1er espace rencontrer après 60 string
        return substr($content, 0, $lastSpace) . '...';
    }
}