<?php

namespace ModelBundle\Protocol;

use \Doctrine\ORM\QueryBuilder;

interface ConvenientRepositoryInterface {

    public function qb(array $options = array()) : QueryBuilder;

    public function qb_active(array $options = array()) : QueryBuilder;

    public function qb_delete(array $options = array()) : QueryBuilder;

    public function findOneById($id);

    public function findOneActiveById($id);

    public function findAllActiveByIdArray(array $ids) : array;

    public function count(QueryBuilder $qb) : int;
}
