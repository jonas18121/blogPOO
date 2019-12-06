'use strict';
// trie de la table en cliquant dans les en-têtes

const TBODY = document.querySelector('tbody');
const TH    = document.querySelectorAll('th');
const TR    = TBODY.querySelectorAll('tr');

/**
 * @param $ids - id de l'emplacement du th qui a été cliquer dans l'en-tête du tableau 
 * exemple [0 => #, 1 => titre, 2 => créer un article]
 * @param $bool - boolean true or false
 * @return nomber -1 and +1 or 0
 */
const COMPARE = function($ids, $bool)
{
    return function($row1, $row2)
    { 
        const TDVALUE = function($row, $ids)
        {   
            return $row.children[$ids].textContent;
        };
        const TRI = function($textColonne1,$textColonne2){

            
            if($textColonne1 !== '' && $textColonne2 !== '' && !isNaN($textColonne1) && !isNaN($textColonne2))
            {
                return $textColonne1 - $textColonne2;
            }
            else
            {
                return $textColonne2.toString().localeCompare($textColonne1);
            };
            
            return $textColonne1 !== '' && $textColonne2 !== '' && !isNaN($textColonne1) && !isNaN($textColonne2) ? $textColonne1 - $textColonne2 : $textColonne1.toString().localeCompare($textColonne2);
        };
        return TRI(TDVALUE($bool ? $row1 : $row2, $ids), TDVALUE($bool ? $row2 : $row1, $ids));
    }
}


TH.forEach(function($th){
    $th.addEventListener('click', function(){
        let classe = Array.from(TR).sort(COMPARE(Array.from(TH).indexOf($th), this.$bool = !this.$bool));
        classe.forEach(function($tr){
            TBODY.appendChild($tr);
        });
    });
});