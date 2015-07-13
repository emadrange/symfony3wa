<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    /**
     * Retourne le nombre de catégorie
     * @author Eric
     * @return mixed
     */
    public function countCategory() {
        $query = $this->createQueryBuilder("cat");

        $query->select("COUNT(cat.titre)");

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Retourne les catégories dont la position > 2
     * @author Eric
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
     * @author Eric
     * @param $text
     * @return array
     */
    public function findTitleCategorysByBeginText($text) {

        $query = $this->createQueryBuilder("cat")
            ->where("cat.titre LIKE :text")
            ->setParameter("text", '%' . $text . '%');

        return $query->getQuery()->getResult();
    }

    /**
     * Retourne les catégories par ordre de position
     * @author Eric
     * @return array
     */
    public function getCategorysByPosition() {

        $query = $this->createQueryBuilder('cat')
            ->orderBy('cat.position');

        return $query->getQuery()->getResult();
    }

    /**
     * Retourne les catégories avec les produits associès
     * @author Eric
     * @return array
     */
    public function getCategorysWithProducts() {

        $query = $this->createQueryBuilder('cat')
            ->select('cat, prod')
            ->leftJoin('cat.products', 'prod')
            ->orderBy('cat.titre', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * Retourne une catégory avec ses produits associés
     * @author Eric
     * @param $dataUrl array
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCategoryWithProductsById($dataUrl) {

        $query = $this->createQueryBuilder('cat')
            ->select('cat, prod')
            ->leftJoin('cat.products', 'prod')
            ->where('cat.id = :id')
            ->orderBy('cat.titre', 'ASC')
            ->setParameter('id', $dataUrl['id']);

        return $query->getQuery()->getSingleResult();
    }
}