<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 27/07/15
 * Time: 14:20
 */

namespace Troiswa\BackBundle\Util;

class Utility
{
    /**
     * Coupe un texte
     * @param $texte
     * @param int $nbchar
     * @return string
     */
    function getLittleDescription($texte, $nbchar = 50)
    {
        return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
    }
}