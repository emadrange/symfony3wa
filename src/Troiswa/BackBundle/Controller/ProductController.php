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


class ProductController extends Controller {

    /**
     * Ajout d'un produit
     * @author Eric
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request) {

        $product = new Product();

        //dump($product);

        //$product->setTitle("Prod");

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

        if ($formProduct->isValid()) {

            //dump($product);
            //die();

            $em = $this->getDoctrine()->getManager();
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository("TroiswaBackBundle:Product")
            ->findBy([], ["title" => "ASC"]);
            //->findAll();

        //dump($products);
        //die();

        return $this->render("TroiswaBackBundle:Product:products.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * Visualisation d'un produit
     * @author Eric
     * @param $idproduct
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($idproduct) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }

        return $this->render("TroiswaBackBundle:Product:product.html.twig", [
            "product" => $product
        ]);
    }

    /**
     * Edition d'un produit
     * @author Eric
     * @param $idproduct
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($idproduct, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }

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

        if ($formEditProduct->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "Le produit a bien été modifié");
            return $this->redirectToRoute("troiswa_back_product_edit", [
                "idproduct" => $product->getId()
            ]);
        }

        return $this->render("TroiswaBackBundle:Product:edit.html.twig", [
            "formProduct" => $formEditProduct->createView()
        ]);
    }

    /**
     * Suppression d'un produit
     * @author Eric
     * @param $idproduct
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($idproduct, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }

        $em->remove($product);
        $em->flush();


        $this->get('session')->getFlashBag()->add("success", "Le produit a bien été supprimé");
        return $this->redirectToRoute("troiswa_back_product_list");
    }

    /**
     * Liste des produits actifs
     * @author Eric
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listactiveAction() {

        //$products = [];

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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listlimitAction(Request $request) {

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
     * @param $idproduct
     * @param $statut
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeActiveAction($idproduct, $statut) {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository("TroiswaBackBundle:Product")
            ->find($idproduct);

        if (!$product) {
            throw $this->createNotFoundException("Produit inconnu...");
        }

        $product->setActive($statut);
        $em->flush();

        return $this->redirectToRoute("troiswa_back_product_list");
    }
}