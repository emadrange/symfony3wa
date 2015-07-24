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
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Product;

class ProductController extends Controller
{

    /**
     * @param Product $product
     *
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
     * Gestion de l'ajout de produit avec leur quantité dans le caddie
     *
     * @author Eric
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("product", options={"mapping": {"idproduct": "id"}})
     */
    public function cartAddAction(Product $product, Request $request){

        $qty = $request->request->getInt('qty');

        if ($qty > 0)
        {
            $session = $request->getSession();
            $session->remove('cart');

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
                $qty = $qty +  $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = ['quantity' => $qty];

            $session->set('cart', json_encode($cart));

        }

        return $this->redirectToRoute('troiswa_front_cart');


        //dump($product);
        //dump($request->request->get('quantity'));
        //die();

        return $this->redirectToRoute('troiswa_front_cart');
    }

    /**
     * Affichage du caddie avec ses produits sélectionnés
     *
     * @param Request $request
     */
    public function cartAction(Request $request)
    {
        $session = $request->getSession();
        dump($session->get('cart'));
        dump(json_decode($session->get('cart')));
        $cart = (array)json_decode($session->get('cart')); // LIGNE PERMETTANT DE RECUP LE PANIER
        //dump($cart);


        $ids_product = [];

        foreach ($cart as $c)
        {
            array_push($ids_product, $c->id_product);
        }

        //$ids_product = implode(',', $ids_product);

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('TroiswaBackBundle:Product')
            ->findProductsByListId($ids_product);

        dump($products);

        die;
    }
}
