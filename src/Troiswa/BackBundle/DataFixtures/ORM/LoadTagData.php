<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
//use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Fixtures tag
     * @author Eric
     * 
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tags = [
            0 => ['tag' => 'train'],
            1 => ['tag' => 'fer'],
            2 => ['tag' => 'chemin'],
            3 => ['tag' => 'rail'],
            4 => ['tag' => 'loco']
        ];
        
        foreach ($tags as $key => $tag)
        {
            $word = new Tag();
            $word->setWord($tag['tag']);
            
            $manager->persist($word);
            $manager->flush();
            
            $this->addReference('tag' . $key, $word);
        }
    }
    
    public function getOrder() {
        return 3;
    }

}

