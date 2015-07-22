<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 22/07/15
 * Time: 16:45
 */

namespace Troiswa\BackBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserCouponRepository
 * @package Troiswa\BackBundle\Repository
 */
class UserCouponRepository extends EntityRepository
{
    /**
     * Retourne les coupons d'un utilisateur
     * @param $id
     * @return array
     */
    public function getCouponsFromUser($id)
    {
        $query = $this->createQueryBuilder('uc')
            ->where('u.id = :id')
            ->leftJoin('uc.user', 'u')
            ->setParameter('id', $id);

        return $query->getQuery()->getResult();
    }
}
