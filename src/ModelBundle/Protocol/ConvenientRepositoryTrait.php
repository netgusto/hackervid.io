<?php

namespace ModelBundle\Protocol;

use \Doctrine\ORM\QueryBuilder;

trait ConvenientRepositoryTrait {

    public function qb(array $options = array()) : QueryBuilder {
        return $this->createQueryBuilder('data');
    }

    public function qb_active(array $options = array()) : QueryBuilder {
        return $this->qb($options);
    }

    public function qb_delete(array $options = array()) : QueryBuilder {
        return $this->em->createQueryBuilder()->delete($this->entity, 'data');
    }

    public function findOneById($id) {
        $query = $this
            ->qb()
            ->andWhere('data.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findOneActiveById($id) {
        $query = $this
            ->qb_active()
            ->andWhere('data.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findAllActiveByIdArray(array $ids) : array {
        $items = $this
            ->qb_active()
            ->andWhere('data.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        # On conserve l'ordre indiqué par le tableau fourni en paramètre
        usort($items, function($a, $b) use ($uids) {
            $posa = array_search($a->getId(), $uids);
            $posb = array_search($b->getId(), $uids);

            return $posa < $posb ? -1 : 1;
        });

        reset($items);
        return $items;
    }

    public function count(QueryBuilder $qb) : int {
        return intval((clone $qb)->select('COUNT(data.id) as nbitems')->getQuery()->getSingleScalarResult());
    }
}