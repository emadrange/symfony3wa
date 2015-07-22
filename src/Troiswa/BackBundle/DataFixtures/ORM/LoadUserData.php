<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {
    
    /**
     * Fixtures User
     * @author Eric
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        
        $users = [
            1 => [
                'username'  => 'roberto.domingo',
                // password = bidule
                'password'  => '$2y$15$jel8qdoeDV4SAaw9Cz5ojeH5130LUlrXgQWfjwRkk5e882wG3iAWC',
                'firstname' => 'Roberto',
                'lastname'  => 'Domingo',
                'email'     => 'roberto.domingo@gmail.com',
                'phone'     => '+33145548976',
                'birthday'  => new \DateTime("1976-07-14 10:00:00.0"),
                'address'   => '25 rue de la Géorgie - 75001 Paris'
            ],
            2 => [
                'username'  => 'marcel',
                // password = bidule
                'password'  => '$2y$15$jel8qdoeDV4SAaw9Cz5ojeH5130LUlrXgQWfjwRkk5e882wG3iAWC',
                'firstname' => 'Marcel',
                'lastname'  => 'Dupont',
                'email'     => 'm.dupont@free.fr',
                'phone'     => '+33152231012',
                'birthday'  => new \DateTime("1987-02-25 10:00:00.0"),
                'address'   => '12 rue de la Pétoire - 45000 Orléans'
            ],
            3 => [
                'username'  => 'jean.maurice',
                // password = bidule
                'password'  => '$2y$15$jel8qdoeDV4SAaw9Cz5ojeH5130LUlrXgQWfjwRkk5e882wG3iAWC',
                'firstname' => 'Jean',
                'lastname'  => 'Maurice',
                'email'     => 'jean.maurice@orange.fr',
                'phone'     => '+33696541337',
                'birthday'  => new \DateTime("1992-10-04 10:00:00.0"),
                'address'   => '730 avenue du Général Poutine - 75016 Paris'
            ]
        ];
        
        foreach ($users as $user) {
            $newUser = new User();
            $newUser->setPseudo($user['username']);
            $newUser->setPassword($user['password']);
            $newUser->setFirstname($user['firstname']);
            $newUser->setLastname($user['lastname']);
            $newUser->setEmail($user['email']);
            $newUser->setPhone($user['phone']);
            $newUser->setBirthday($user['birthday']);
            $newUser->setAddress($user['address']);
            
            $manager->persist($newUser);
            
        }
        
        $manager->flush();
    }
    
    /**
     * 
     * @return int
     */
    public function getOrder()
    {
        return 5;
        // TODO: Implement getOrder() method.
    }
}

