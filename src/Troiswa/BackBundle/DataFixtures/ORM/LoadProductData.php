<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 09/07/15
 * Time: 11:39
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Product;

class LoadProductData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $products = [
            0 => [
                "title" => "EMD FT AT&SF",
                "description" => "EMD FTA/B de la Atchinson, Topeka and Santa Fe cigar band",
                "quantity" => 12,
                "price" => 220,
                "active" => true
            ],
            1 => [
                "title" => "EMD F7A AT&SF",
                "description" => "EMD F7A de la Atchinson, Topeka and Santa Fe passager jaune avec DCC",
                "quantity" => 7,
                "price" => 315.5,
                "active" => true
            ],
            2 => [
                "title" => "Alco RS-3 LV",
                "description" => "Alco RS-3 de la Lehigh Valley avec son",
                "quantity" => 2,
                "price" => 377.20,
                "active" => true
            ],
            3 => [
                "title" => "General Electric GG1 Pennsylvania",
                "description" => "GG1 de la PRR avec DCC",
                "quantity" => 4,
                "price" => 422.20,
                "active" => true
            ],
            4 => [
                "title" => "Alco Big Boy 4-8-8-4 UP",
                "description" => "Big Boy 4-8-8-4 de la Union Pacific",
                "quantity" => 7,
                "price" => 522.00,
                "active" => false
            ]
        ];

        foreach ($products as $product) {
            $prod = new Product();
            $prod->setTitle($product['title']);
            $prod->setDescription($product['description']);
            $prod->setActive($product['active']);
            $prod->setPrice($product['price']);
            $prod->setQuantity($product['quantity']);

            $manager->persist($prod);
        }

        /*$product = new Product();

        $product->setActive(true);
        $product->setPrice(250);
        $product->setTitle("Alco Big Boy");
        $product->setDescription("Big Boy 4014 UP");
        $product->setQuantity(10);

        $manager->persist($product);*/
        $manager->flush();
    }
}