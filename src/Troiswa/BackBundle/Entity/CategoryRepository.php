<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    /**
     * Retourne le nombre de catégorie
     * @return mixed
     */
    public function countCategory() {
        $query = $this->createQueryBuilder("cat");

        $query->select("COUNT(cat.titre)");

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Retourne les catégories dont la position > 2
     * @param $position
     * @return array
     */
    public function findCategorysByPosition($position) {

        $query = $this->createQueryBuilder("cat")
            ->where("cat.position > :position")
            ->setParameter("position", $position);

        return $query->getQuery()->getResult();
    }

    /**
     * Rtourne les catégories dont le titre commence par "le"
     * @param $text
     * @return array
     */
    public function findTitleCategorysByBeginText($text) {

        $query = $this->createQueryBuilder("cat")
            ->where("cat.titre LIKE :text")
            ->setParameter("text", '%' . $text . '%');

        return $query->getQuery()->getResult();
    }
}