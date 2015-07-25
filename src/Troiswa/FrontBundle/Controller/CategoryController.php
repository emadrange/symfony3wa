<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 23/07/15
 * Time: 17:05
 */

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            ->findBy([], ["position" => "ASC"]);

        return $this->render('TroiswaFrontBundle:Category:category-list.html.twig', [
            'categorys' => $categorys
        ]);
    }

    /**
     * Visualise une catégorie avec les produits associés
     * @author Eric
     * 
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("category", options={
     *      "mapping": {"idcategory": "id"},
     *      "repository_method" = "getCategoryWithProductsById"
     * })
     */
    public function showAction(Category $category, Request $request)
    {
        return $this->render('TroiswaFrontBundle:Category:show.html.twig', [
            'category' => $category
        ]);
    }
}