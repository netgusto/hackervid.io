<?php

namespace ModelBundle\Repository;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityRepository;

use AppBundle\Services\Utils;

use ModelBundle\Protocol\ServiceRepositoryInterface;
use ModelBundle\Protocol\ServiceRepositoryTrait;

use ModelBundle\Protocol\ConvenientRepositoryInterface;
use ModelBundle\Protocol\ConvenientRepositoryTrait;

use ModelBundle\Entity\ItemComment;

class ItemCommentRepository extends EntityRepository implements ServiceRepositoryInterface, ConvenientRepositoryInterface {
    use ServiceRepositoryTrait;
    use ConvenientRepositoryTrait;

    protected $validator;
    protected $utils;

    public function setDependencies(ValidatorInterface $validator, Utils $utils) {
        $this->validator = $validator;
        $this->utils = $utils;
    }

    public function create(ItemComment &$itemcomment) : array {

        # Validation
        $errors = $this->utils->errorsToStringArray(
            $this->validator->validate($itemcomment, null, ['create'])
        );

        if(count($errors) > 0) return $errors;

        # Creation
        $this->em->persist($itemcomment);
        $this->em->flush();

        return [];
    }

    public function incrementPoints(ItemComment $comment) {
        $this->em->createQuery(
            'UPDATE ModelBundle:ItemComment comment
                SET comment.points = comment.points+1
                WHERE comment.id = :commentid
            '
        )->setParameter('commentid', $comment->getId())
        ->execute();
        $this->em->refresh($comment);
    }
}
