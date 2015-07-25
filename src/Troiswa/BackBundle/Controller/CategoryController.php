<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 03/07/15
 * Time: 09:41
 */

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Category;
use Troiswa\BackBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CategoryController extends Controller {

    /**
     * Ajout d'une catégorie
     * @author Eric
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $category = new Category();

        $formCategory = $this->createForm(new CategoryType(), $category, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
            ->add("submit", "submit", [
            "label" => "Enregistrer",
            "attr" => [
                "class" => "btn btn-success"
            ]
        ]);

        $formCategory->handleRequest($request);

        if ($formCategory->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "La catégorie a bien été ajoutée");
            return $this->redirectToRoute("troiswa_back_category_add");
        }

        return $this->render("TroiswaBackBundle:Category:add.html.twig", [
            "formCategory" => $formCategory->createView()
        ]);
    }

    /**
     * Liste des catégories
     * @author Eric
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorys = $em->getRepository("TroiswaBackBundle:Category")
            ->getCategorysWithProducts();
            //->findAll();

        return $this->render("TroiswaBackBundle:Category:list.html.twig", [
            "categorys" => $categorys
        ]);
    }

    /**
     * Visualisation d'une catégorie
     * @author Eric
     * 
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("category", options={
     *      "mapping": {"idcategory": "id"},
     *      "repository_method": "getCategoryWithProductsById"})
     */
    public function showAction(Category $category, Request $request)
    {
        /*$em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("TroiswaBackBundle:Category")
            ->find($idcategory);

        if (!$category) {
            throw $this->createNotFoundException("Catégorie inconnue...");
        }*/

        return $this->render("TroiswaBackBundle:Category:show.html.twig", [
            "category" => $category
        ]);

    }

    /**
     * Edition d'une catégorie
     * @author Eric
     * 
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("category", options={"mapping": {"idcategory": "id"}})
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        /*$category = $em->getRepository("TroiswaBackBundle:Category")
            ->find($idcategory);

        if (!$category) {
            throw $this->createNotFoundException("Catégorie inconnue...");
        }*/

        $formCategory = $this->createForm(new CategoryType(), $category, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("submit", "submit", [
            "label" => "Enregistrer",
            "attr" => [
                "class" => "btn btn-success"
            ]
        ]);

        $formCategory->handleRequest($request);

        if ($formCategory->isValid())
        {

            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "La catégorie a bien été modifiée");
            return $this->redirectToRoute("troiswa_back_category_edit", [
                "idcategory" => $category->getId()
            ]);
        }

        return $this->render("TroiswaBackBundle:Category:edit.html.twig", [
            "formCategory" => $formCategory->createView()
        ]);
    }

    /**
     * Suppression d'une catégorie
     * @author Eric
     * 
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @ParamConverter("category", options={"mapping": {"idcategory": "id"}})
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        /*$category = $em->getRepository("TroiswaBackBundle:Category")
            ->find($idcategory);

        if (!$category) {
            throw $this->createNotFoundException("Catégorie inconnu...");
        }*/

        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashBag()->add("success", "La catégorie a bien été supprimée");
        
        return $this->redirectToRoute("troiswa_back_category_list");
    }

    /**
     * Liste des catégories pour la sidebar avec {{ render }}
     * @author Eric
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allCategoryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorys = $em->getRepository("TroiswaBackBundle:Category")
            ->findBy([], ["position" => "ASC"]);

        return $this->render("TroiswaBackBundle:Category:category-sidebar.html.twig", [
            "categorys" => $categorys
        ]);
    }

    /**
     * 
     * @return type
     */
    public function categoryAction()
    {
        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        return $this->render("TroiswaBackBundle:Category:index.html.twig", [
            "categorys" => $categories
        ]);
    }

    /**
     * 
     * @param type $iditem
     * @return type
     * @throws type
     */
    public function infoAction($iditem)
    {

        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        if (!isset($categories[$iditem])) {
            throw $this->createNotFoundException("Catégorie inconnue...");
        }

        return $this->render("TroiswaBackBundle:Category:show.html.twig", [
            "category" => $categories[$iditem]
        ]);
    }
}
