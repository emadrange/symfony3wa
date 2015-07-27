<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 02/07/15
 * Time: 14:58
 */

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Product;
use Troiswa\BackBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class ProductController extends Controller {

    /**
     * Ajout d'un produit
     * @author Eric
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        // Interdit l'accès aux utilisateur si ils ne sont pas administrateur
        /*if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette page');
        }*/

        $product = new Product();

        $formProduct = $this->createForm(new ProductType(), $product, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("submit", "submit", [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);

        $formProduct->handleRequest($request);

        if ($formProduct->isValid())
        {

            $cover = $product->getCover();
            $cover->setAlt($product->getTitle());

            //$cover->upload();

            $em = $this->getDoctrine()->getManager();

            // permet au slug d'incrémenter au cas d'un doublon de titre
            $em->getFilters()->disable('softdeleteable');

            // plus besoin de persister $cover car c'est en CASCADE
            //$em->persist($cover);
            $em->persist($product);

            //$product->setTitle("titre modifié après persist");

            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "Le produit a bien été ajouté");
            return $this->redirectToRoute("troiswa_back_product_add");
        }

        return $this->render("TroiswaBackBundle:Product:add.html.twig", [
            "formProduct" => $formProduct->createView()
        ]);
    }

    /**
     * Liste des produits
     * @author Eric
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository("TroiswaBackBundle:Product")
            ->findAllProductWithAllElement(true);
            //->findBy([], ["title" => "ASC"]);
            //->findAll();

        //dump($products);
        //die();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render("TroiswaBackBundle:Product:list.html.twig", [
            //"products" => $products
            "pagination" => $pagination
        ]);
    }

    /**
     * Visualisation d'un produit
     * @author Eric
     * 
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("product", options={
     *      "mapping": {"idproduct": "id"},
     *      "repository_method" = "findOneProductWithAllElement"
     * })
     */
    public function showAction(Product $product)
    {
        //var_dump($product);
        //dump($product);
        //die();

        /* les commandes ci-dessous sont exécutées par l'annotation @ParamConverter */
        /*$em = $this->getDoctrine()->getManager();

        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }*/

        return $this->render("TroiswaBackBundle:Product:show.html.twig", [
            "product" => $product
        ]);
    }

    /**
     * Edition d'un produit
     * @author Eric
     * 
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("product", options={"mapping": {"idproduct":"id"}})
     *
     * Cette page n'est accessible que par l'administrateur
     *
     *  Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Product $product, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /*$product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }*/

        $formEditProduct = $this->createForm(new ProductType(), $product, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("submit", "submit", [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);

        $formEditProduct->handleRequest($request);

        if ($formEditProduct->isValid())
        {
            $cover = $product->getCover();
            $cover->setAlt($product->getTitle());
            
            //$cover->upload();

            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "Le produit a bien été modifié");
            return $this->redirectToRoute("troiswa_back_product_edit", [
                "idproduct" => $product->getId(),
            ]);
        }

        return $this->render("TroiswaBackBundle:Product:edit.html.twig", [
            "formProduct" => $formEditProduct->createView(),
            "idProduct" => $product->getId()
        ]);
    }

    /**
     * Suppression d'un produit
     * @author Eric
     * 
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @ParamConverter("product", options={"mapping": {"idproduct":"id"}})
     */
    public function deleteAction(Product $product, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /*$product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }*/

        $em->remove($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add("success", "Le produit a bien été supprimé");
        
        return $this->redirectToRoute("troiswa_back_product_list");
    }

    /**
     * Liste des produits actifs
     * @author Eric
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listActiveAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository("TroiswaBackBundle:Product")
            ->findBy(["active" => true], ["title" => "ASC"]);

        //dump($products);
        //die();

        return $this->render("TroiswaBackBundle:Product:active-products.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * Liste des produits avec limite
     * @author Eric
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listLimitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $limit = $request->query->get("limit");

        if (empty($limit) || $limit == "all" || $limit <= 0)
        {
            $limit = null;
        }

        $products = $em->getRepository("TroiswaBackBundle:Product")
                ->findBy([], ["title" => "ASC"], $limit);

        return $this->render("TroiswaBackBundle:Product:limit-products.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * Changement de l'état active
     * @author Eric
     * 
     * @param $idproduct
     * @param $statut
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeActiveAction($idproduct, $statut)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product)
        {
            throw $this->createNotFoundException("Produit inconnu...");
        }

        $product->setActive($statut);
        $em->flush();

        return $this->redirectToRoute("troiswa_back_product_list");
    }

    /**
     * Retourne une liste de produit par prix
     * @author Eric
     * 
     * @return type
     */
    public function listProductByPriceAction()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $products = $em->getRepository('TroiswaBackBundle:Product')
            ->getExpensiveProductsByLimit(3);

        return $this->render('TroiswaBackBundle:Product:list-product-for-error.html.twig', [
            "products" => $products
        ]);
    }
}
