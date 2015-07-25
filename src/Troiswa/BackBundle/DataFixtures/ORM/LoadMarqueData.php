<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 16/07/15
 * Time: 10:24
 */
namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
//use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Marque;

class LoadMarqueData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     * @author Eric
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $brands = [
          0 => [
              'title' => 'Bachmann',
              'description' => 'HO, N, O, G, Ho3, '
          ],
          1 => [
              'title' => 'Intermountain',
              'description' => 'Spécialiste de la EMD série F'
          ],
          2 => [
              'title' => 'Lessieur',
              'description' => 'La meilleur'
          ]
        ];

        foreach ($brands as $key => $brand)
        {
            $marque = new Marque();
            $marque->setTitle($brand['title']);
            $marque->setDescription($brand['description']);

            $manager->persist($marque);
            $manager->flush();

            $this->addReference('brand' . $key, $marque);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}