<?php

namespace ModelBundle\Protocol;

use Doctrine\ORM\EntityManager;

interface ServiceRepositoryInterface {
    public function initServiceRepository(EntityManager $em, string $entity);
    public function createQueryBuilder($alias, $indexBy = NULL);
    public function getEntityManager() : EntityManager;
}
