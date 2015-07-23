<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 23/07/15
 * Time: 17:05
 */

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * Retourne la liste des catégories pour le sidebar
     * @author Eric
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorys = $em->getRepository("TroiswaBackBundle:Category")
            ->findBy([], ["titre" => "ASC"]);

        return $this->render('TroiswaFrontBundle:Category:category-list.html.twig', [
            'categorys' => $categorys
        ]);
    }
}