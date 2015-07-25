<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 09/07/15
 * Time: 11:59
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

//use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Fixtures catégorie
     * @author Eric
     * 
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $categorys = [
            0 => [
                "titre" => "Diesel",
                "description" => "Switcher, road-switcher, passager",
                "position" => 1,
                "reference" => "cat0"
            ],
            1 => [
                "titre" => "Vapeur",
                "description" => "Switcher, passager, freight",
                "position" => 1,
                "reference" => "cat1"
            ],
            2 => [
                "titre" => "Electrique",
                "description" => "Switcher, road-switcher, passager",
                "position" => 0,
                "reference" => "cat2"
            ],
            3 => [
                "titre" => "Wagon",
                "description" => "Tout type de wagon",
                "position" => 2,
                "reference" => "cat3"
            ],
            4 => [
                "titre" => "Le moteur",
                "description" => "Motorisation",
                "position" => 3,
                "reference" => "cat4"
            ],
        ];

        foreach($categorys as $category)
        {
            $cat = new Category();
            $cat->setTitre($category["titre"]);
            $cat->setDescription($category["description"]);
            $cat->setPosition($category["position"]);

            $manager->persist($cat);
            $manager->flush();

            $this->addReference($category["reference"], $cat);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return 1;
    }
}