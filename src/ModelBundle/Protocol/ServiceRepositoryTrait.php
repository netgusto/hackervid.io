<?php

namespace ModelBundle\Protocol;

use Doctrine\ORM\EntityManager;

trait ServiceRepositoryTrait {

    protected $em;
    protected $entity;

    public function initServiceRepository(EntityManager $em, string $entity) {
        $this->em = $em;
        $this->entity = $entity;
    }

    public function createQueryBuilder($alias, $indexBy = NULL) {
        return $this->em->createQueryBuilder()->select($alias)->from($this->entity, $alias);
    }

    public function getEntityManager() : EntityManager {
        return $this->em;
    }
}
