<?php

namespace ModelBundle\Repository;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityRepository;

use ModelBundle\Protocol\ServiceRepositoryInterface;
use ModelBundle\Protocol\ServiceRepositoryTrait;

use ModelBundle\Protocol\ConvenientRepositoryInterface;
use ModelBundle\Protocol\ConvenientRepositoryTrait;

use ModelBundle\Entity\ItemCommentVote;

use AppBundle\Services\Utils;

class ItemCommentVoteRepository extends EntityRepository implements ServiceRepositoryInterface, ConvenientRepositoryInterface {
    use ServiceRepositoryTrait;
    use ConvenientRepositoryTrait;

    protected $validator;
    protected $utils;

    public function setDependencies(ValidatorInterface $validator, Utils $utils) {
        $this->validator = $validator;
        $this->utils = $utils;
    }

    public function create(ItemCommentVote &$vote) : array {

        # Validation
        $errors = $this->utils->errorsToStringArray(
            $this->validator->validate($vote, null, ['create'])
        );

        if(count($errors) > 0) return $errors;

        # Creation
        $this->em->persist($vote);
        $this->em->flush();

        return [];
    }
}
