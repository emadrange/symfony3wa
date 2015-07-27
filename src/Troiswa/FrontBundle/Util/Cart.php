<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 27/07/15
 * Time: 14:55
 */

namespace Troiswa\FrontBundle\Util;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class utilisée en service (troiswa_front.cart)
 * Class Cart
 * @package Troiswa\FrontBundle\Util
 */
class Cart
{

    /**
     * @var Session
     */
    private $session;

    private $entityManager;

    /**
     * @param Session $session
     */
    public function __construct(Session $session, $em)
    {
        $this->session = $session;
        $this->entityManager = $em;
    }

    /**
     * Retourne le caddie
     * @author Eric
     *
     * @return mixed
     */
    public function getCart()
    {
        return json_decode($this->session->get('cart'), true);
    }

    /**
     * Vide le caddie
     * @author Eric
     */
    public function clearCart()
    {
        //$cart = [];
        //$this->session->set('cart', json_encode($cart));

        $this->session->remove('cart');
    }

    /**
     * Retourne les éléments du caddie
     * @author Eric
     *
     * @return mixed
     */
    public function getProducts()
    {
        $cart = json_decode($this->session->get('cart'), true);

        if ($cart)
        {
            //$em = $this->getDoctrine()->getManager();

            $idProducts = array_keys($cart);
            $products = $this->entityManager->getRepository('TroiswaBackBundle:Product')
                ->findProductsByListId($idProducts);

            return $products;
        }
    }

    /**
     * Ajoute un élément avec sa quantité dans le caddie
     * @author Eric
     *
     * @param $id
     * @param $quantity
     */
    public function add($id, $quantity)
    {
        if ($quantity > 0)
        {
            // pour effacer la session sans fermer le navigateur
            //$this->session->remove('cart');

            if ($this->session->get('cart'))
            {
                $cart = json_decode($this->session->get('cart'), true);
            }
            else
            {
                $cart = [];
            }

            if (array_key_exists($id, $cart))
            {
                $quantity += $cart[$id]['quantity'];
            }

            $cart[$id] = ['quantity' => $quantity];

            $this->session->set('cart', json_encode($cart));

        }
    }

    /**
     * Supprime un élément du caddie
     * @author Eric
     *
     * @param $id
     */
    public function remove($id)
    {
        $cart = json_decode($this->session->get('cart'), true);

        if (array_key_exists($id, $cart))
        {
            unset($cart[$id]);
            $this->session->set('cart', json_encode($cart));
        }
    }

    /**
     * Incrémente de 1 la quantité d'un élément
     * @author Eric
     *
     * @param $id
     */
    public function increment($id)
    {
        $cart = json_decode($this->session->get('cart'), true);

        if (array_key_exists($id, $cart))
        {
            $cart[$id]['quantity'] += 1;
            $this->session->set('cart', json_encode($cart));
        }
    }

    /**
     * Décrémente de 1 la quantité d'un élément et l'efface si la quantité = 0
     * @author Eric
     *
     * @param $id
     */
    public function decrement($id)
    {
        $cart = json_decode($this->session->get('cart'), true);

        if (array_key_exists($id, $cart))
        {
            $cart[$id]['quantity'] -= 1;

            if ($cart[$id]['quantity'] == 0)
            {
                unset($cart[$id]);
            }

            $this->session->set('cart', json_encode($cart));
        }
    }
}