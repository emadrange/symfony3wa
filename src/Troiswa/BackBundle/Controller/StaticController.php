<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 02/07/15
 * Time: 12:23
 */

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticController extends Controller {

    public function indexAction()
    {
        return $this->render("TroiswaBackBundle:cgv:index.html.twig", [
            "firstname" => "éric",
            "lastname" => "madrange",
            "age" => "20"
        ]);
    }

    public function trainingAction($chaine)
    {
        return $this->render("TroiswaBackBundle:Statics:training.html.twig", [
            "chaine" => $chaine
        ]);
    }

    public function heritageAction()
    {
       return $this->render("TroiswaBackBundle:Statics:heritage.html.twig");
    }

    public function templatingAction()
    {
       return $this->render("TroiswaBackBundle:Statics:templating.html.twig");
    }
}