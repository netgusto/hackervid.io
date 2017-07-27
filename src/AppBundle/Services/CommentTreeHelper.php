<?php

namespace AppBundle\Services;

use ModelBundle\Entity\Item;
use ModelBundle\Repository\ItemCommentRepository;

class CommentTreeHelper {

    public function __construct(ItemCommentRepository $itemcommentrepo) {
        $this->itemcommentrepo = $itemcommentrepo;
    }

    public function getCommentTreeForItem(Item $item, callable $propify, $rootid = null) : array {
        # Pre-fetching all comments in one query
        $comments = $this->itemcommentrepo->qb_active()
            ->andWhere('data.item = :item')
                ->setParameter('item', $item)
            ->addOrderBy('data.points', 'DESC')
            ->addOrderBy('data.creationdate', 'DESC')
            ->getQuery()
            ->getResult();

        $pointers = [];

        foreach($comments as $comment) {
            $parent = $comment->getParent();
            $pointers[$comment->getId()] = $propify($comment);
            $pointers[$comment->getId()]['children'] = [];
            $pointers[$comment->getId()]['parent'] = is_null($parent) ? null : $parent->getId();
        }

        $roots = [];

        foreach($pointers as $id => $val) {
            $pointer =& $pointers[$id];

            $hasparent = !is_null($pointer['parent']);

            if($hasparent) {
                $pointers[$pointer['parent']]['children'][] =& $pointer;
            }

            if(is_null($rootid)) {
                if(!$hasparent) {
                    $roots[] =& $pointer;
                }
            } else {
                if($id === $rootid) {
                    $roots[] =& $pointer;
                }
            }
        }

        if(!is_null($rootid) && count($roots) > 0) {
            $roots = $roots[0]['children'];
        }

        return $roots;
    }
}