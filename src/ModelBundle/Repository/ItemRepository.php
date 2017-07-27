<?php

namespace ModelBundle\Repository;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityRepository;

use ModelBundle\Protocol\ServiceRepositoryInterface;
use ModelBundle\Protocol\ServiceRepositoryTrait;

use ModelBundle\Protocol\ConvenientRepositoryInterface;
use ModelBundle\Protocol\ConvenientRepositoryTrait;

use ModelBundle\Entity\Item;

use AppBundle\Services\Utils;

class ItemRepository extends EntityRepository implements ServiceRepositoryInterface, ConvenientRepositoryInterface {
    use ServiceRepositoryTrait;
    use ConvenientRepositoryTrait;

    protected $validator;
    protected $utils;

    public function setDependencies(ValidatorInterface $validator, Utils $utils) {
        $this->validator = $validator;
        $this->utils = $utils;
    }

    public function create(Item &$item, bool $flush = true) : array {

        # Validation
        $errors = $this->utils->errorsToStringArray(
            $this->validator->validate($item, null, ['create'])
        );

        if(count($errors) > 0) return $errors;

        # Creation
        $this->em->persist($item);
        if($flush) $this->em->flush();

        return [];
    }

    public function update(Item &$item, bool $flush = true) : array {

        # Validation
        $errors = $this->utils->errorsToStringArray(
            $this->validator->validate($item, null, ['update'])
        );

        if(count($errors) > 0) return $errors;

        # Creation
        $this->em->persist($item);
        if($flush) $this->em->flush();

        return [];
    }

    public function incrementPoints(Item $item) {
        $this->em->createQuery(
            'UPDATE ModelBundle:Item item
                SET item.points = item.points+1
                WHERE item.id = :itemid
            '
        )->setParameter('itemid', $item->getId())
        ->execute();
        $this->em->refresh($item);
    }

    public function fetchTopRankingRange(int $start, int $number, \DateTime $earliestdate) : array {
        return $this->qb_active()
            ->andWhere("data.creationdate >= :earliestdate")
                ->setParameter("earliestdate", $earliestdate)
            ->orderBy("data.momentum", "DESC")
            ->setFirstResult($start)
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

    public function fetchNewestRange(int $start, int $number) : array {
        return $this->qb_active()
            ->orderBy("data.creationdate", "DESC")
            ->setFirstResult($start)
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }
}
