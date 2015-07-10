<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 09/07/15
 * Time: 11:59
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $categorys = [
            0 => [
                "titre" => "Diesel",
                "description" => "Switcher, road-switcher, passager",
                "position" => 1
            ],
            1 => [
                "titre" => "Vapeur",
                "description" => "Switcher, passager, freight",
                "position" => 1
            ],
            2 => [
                "titre" => "Electrique",
                "description" => "Switcher, road-switcher, passager",
                "position" => 0
            ],
            3 => [
                "titre" => "Wagon",
                "description" => "Tout type de wagon",
                "position" => 2
            ],
            3 => [
                "titre" => "Le moteur",
                "description" => "Motorisation",
                "position" => 1
            ],
        ];

        foreach($categorys as $category) {
            $cat = new Category();
            $cat->setTitre($category["titre"]);
            $cat->setDescription($category["description"]);
            $cat->setPosition($category["position"]);

            $manager->persist($cat);
        }

        $manager->flush();
    }
}