<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 24/07/15
 * Time: 11:55
 */

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Product;

class ProductController extends Controller
{

    /**
     * Visualise un produit
     * @author Eric
     * 
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("product", options={
     *      "mapping": {"idproduct": "id"},
     *      "repository_method" = "findOneProductWithAllElement"
     * })
     */
    public function showAction(Product $product, Request $request)
    {
        return $this->render('TroiswaFrontBundle:Product:show.html.twig', [
            'product' => $product
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('TroiswaBackBundle:Product')
                ->findAllProductWithAllElement(true);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
        
        return $this->render('TroiswaFrontBundle:Product:list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Gestion de l'ajout de produit avec leur quantité dans le caddie
     * @author Eric
     * 
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("product", options={"mapping": {"idproduct": "id"}})
     */
    public function cartAddAction(Product $product, Request $request)
    {
        $quantity = $request->request->getInt('quantity');

        // récupération du service
        $cart = $this->get('troiswa_front.cart');
        // method du service
        $cart->add($product->getId(), $quantity);

        /*if ($quantity > 0)
        {
            $session = $request->getSession();
            //$session->remove('cart');

            if ($session->get('cart'))
            {
                $cart = json_decode($session->get('cart'), true);
            }
            else
            {
                $cart = [];
            }

            if (array_key_exists($product->getId(), $cart))
            {
                $quantity = $quantity +  $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = ['quantity' => $quantity];

            $session->set('cart', json_encode($cart));

        }*/
        
        //dump($product);
        //dump($request->request->get('quantity'));
        //die();

        return $this->redirectToRoute('troiswa_front_cart');
    }

    /**
     * Caddie avec ses produits sélectionnés
     * @author Eric
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAction(Request $request)
    {
        /*$session = $request->getSession();
        $cart = json_decode($session->get('cart'), true);

        if ($cart)
        {
            $em = $this->getDoctrine()->getManager();
            $idProducts = array_keys($cart);
            $products = $em->getRepository('TroiswaBackBundle:Product')
                ->findProductsByListId($idProducts);
        }*/

        $cart = $this->get('troiswa_front.cart');

        return $this->render('TroiswaFrontBundle:Product:cart.html.twig', [
            'products' => $cart->getProducts(),
            'cart' => $cart->getCart()
        ]);
    }

    /**
     * Supprime un élément du caddie (Ajax)
     * @author Eric
     *
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("product", options={"mapping": {"idproduct": "id"}})
     */
    public function cartRemoveAction(Product $product, Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $cart = $this->get('troiswa_front.cart');
            $cart->remove($product->getId());

            return new JsonResponse("Produit supprimé");
        }

        /*$session = $request->getSession();
        $cart = json_decode($session->get('cart'), true);

        if (array_key_exists($product->getId(), $cart))
        {
            unset($cart[$product->getId()]);
            $session->set('cart', json_encode($cart));
        }*/

        return $this->redirectToRoute('troiswa_front_cart');
    }

    /**
     * Incrémente la quantité d'un produit (Ajax)
     * @author Eric
     *
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("product", options={"mapping": {"idproduct": "id"}})
     */
    public function cartIncrementAction(Product $product, Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $cart = $this->get('troiswa_front.cart');
            $cart->increment($product->getId());

            return new JsonResponse("Produit ajouté");
        }

        /*$session = $request->getSession();
        $cart = json_decode($session->get('cart'), true);

        if (array_key_exists($product->getId(), $cart))
        {
            $cart[$product->getId()]['quantity'] += 1;
            $session->set('cart', json_encode($cart));
        }*/

        return $this->redirectToRoute('troiswa_front_cart');
    }

    /**
     * Décrémente la quantité d'un produit (Ajax)
     * @author Eric
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("product", options={"mapping": {"idproduct": "id"}})
     */
    public function cartDecrementAction(Product $product, Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $cart = $this->get('troiswa_front.cart');
            $cart->decrement($product->getId());

            return new JsonResponse("Produit enlevé");
        }

        return $this->redirectToRoute('troiswa_front_cart');
    }

    /**
     * Vide le caddie
     * @author Eric
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearCartAction()
    {
        $cart = $this->get('troiswa_front.cart');

        $cart->clearCart();

        return $this->redirectToRoute('troiswa_front_cart');
    }

    public function cartCouponAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $coupon = $em->getRepository('TroiswaBackBundle:Coupon')
            ->findOneByCode($request->request->get('coupon'));

        if ($coupon)
        {

        }

        //dump($coupon);
        //die;

        return $this->redirectToRoute('troiswa_front_cart');
    }
}
