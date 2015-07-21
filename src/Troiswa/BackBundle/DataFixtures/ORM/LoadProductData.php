<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 09/07/15
 * Time: 11:39
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
//use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Fixtures produits
     * @author Eric
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $products = [
            0 => [
                "title" => "EMD FT AT&SF",
                "description" => "EMD FTA/B de la Atchison, Topeka and Santa Fe cigar band",
                "quantity" => 12,
                "price" => 220,
                "active" => true
            ],
            1 => [
                "title" => "EMD F7A AT&SF",
                "description" => "EMD F7A de la Atchison, Topeka and Santa Fe passager jaune avec DCC",
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
            ],
            5 => [
                "title" => "Baldwin RF-16 Baltimore & Ohio",
                "description" => "Baldwin Sharknose avec DCC et son",
                "quantity" => 1,
                "price" => 267.30,
                "active" => false
            ],
            6 => [
                "title" => "Transfert caboose RI",
                "description" => "Transfert caboose de la Chicago, Rock Island and Pacific",
                "quantity" => 2,
                "price" => 46.20,
                "active" => true
            ],
            7 => [
                "title" => "Citerne 3 dômes ESSO",
                "description" => "Citerne 3 dôme avec chassi métal",
                "quantity" => 0,
                "price" => 42.00,
                "active" => true
            ]
        ];

        foreach ($products as $product) {
            $prod = new Product();
            $prod->setTitle($product['title']);
            $prod->setDescription($product['description']);
            $prod->setActive($product['active']);
            $prod->setPrice($product['price']);
            $prod->setQuantity($product['quantity']);
            $prod->setCat($this->getReference('cat' . rand(0, 4)));
            $prod->setMarque($this->getReference('brand' . rand(0, 2)));
            $prod->addTag($this->getReference('tag' . rand(0, 4)));

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

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
        // TODO: Implement getOrder() method.
    }
}