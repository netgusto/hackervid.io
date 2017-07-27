<?php

namespace ModelBundle\Repository;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use AppBundle\Services\Utils;

use ModelBundle\Protocol\ServiceRepositoryInterface;
use ModelBundle\Protocol\ServiceRepositoryTrait;

use ModelBundle\Protocol\ConvenientRepositoryInterface;
use ModelBundle\Protocol\ConvenientRepositoryTrait;

use ModelBundle\Entity\User;

class UserRepository extends EntityRepository implements ServiceRepositoryInterface, ConvenientRepositoryInterface {

    use ServiceRepositoryTrait;
    use ConvenientRepositoryTrait;

    protected $validator;
    protected $utils;

    public function setDependencies(ValidatorInterface $validator, Utils $utils) {
        $this->validator = $validator;
        $this->utils = $utils;
    }

    public function findOneActiveByUsername(string $username) {
        return $this->qb_active()
            ->andWhere('data.username = :username')
                ->setParameter('username', $username)
            ->getQuery()->getOneOrNullResult();
    }

    public function create(User &$user) : array {

        # Validation
        $errors = $this->utils->errorsToStringArray(
            $this->validator->validate($user, null, ['create'])
        );

        if(count($errors) > 0) return $errors;

        # Creation
        $this->em->persist($user);
        $this->em->flush();

        return [];
    }

    public function incrementKarma(User $user) {
        $this->em->createQuery(
            'UPDATE ModelBundle:User user
                SET user.karma = user.karma+1
                WHERE user.id = :userid
            '
        )->setParameter('userid', $user->getId())
        ->execute();
        $this->em->refresh($user);
    }
}
