<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 29/07/15
 * Time: 16:35
 */

namespace Troiswa\BackBundle\Twig;

class Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            'price' => new \Twig_Filter_Method($this, 'priceFilter'),
            'civility' => new \Twig_Filter_Method($this, 'civilityFilter'),
            'trunk' => new \Twig_Filter_Method($this, 'trunkFilter')
        ];
    }

    /**
     * Filtre pour ajouter le signe € avec formatage pour un nombre
     * @author Eric
     *
     * @param $number
     * @return string
     */
    public function priceFilter($number, $decimal = 2, $separatorDecimal = ',', $separatorMille = " ")
    {

        $price = number_format($number, $decimal, $separatorDecimal, $separatorMille);

        return $price . " €";
    }

    /**
     * Filtre pour afficher la civilité avec un boolean
     * @author Eric
     *
     * @param $civility
     * @return string
     */
    public function civilityFilter($civility)
    {
        if ($civility)
        {
            $c = "Mr.";
        }
        else
        {
            $c = "Mme.";
        }

        return $c;
    }

    /**
     * Filtre pour tronquer un texte
     * @author Eric
     *
     * @param $texte
     * @param int $nbr
     * @return string
     */
    public function trunkFilter($texte, $nbr = 50)
    {
        return (strlen($texte) > $nbr ? substr(substr($texte,0,$nbr),0,strrpos(substr($texte,0,$nbr)," "))." ..." : $texte);
    }

    public function getFunctions()
    {
        return [
            'age' => new \Twig_Function_Method($this, 'ageFunction')
        ];
    }

    /**
     * Méthode pour calculer l'age
     * @param $date
     * @return string
     */
    public function ageFunction($date){

        $now = new \DateTime('now');
        $diff = $date->diff($now);
        return $diff->format('%y'). 'ans';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return "troiswa_back.twig_extension";
    }
}