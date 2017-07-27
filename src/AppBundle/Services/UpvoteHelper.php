<?php

namespace AppBundle\Services;

use AppBundle\Services\Utils;

use ModelBundle\Entity\User;
use ModelBundle\Entity\Item;
use ModelBundle\Entity\ItemComment;
use ModelBundle\Entity\ItemVote;
use ModelBundle\Entity\ItemCommentVote;

use ModelBundle\Repository\UserRepository;
use ModelBundle\Repository\ItemRepository;
use ModelBundle\Repository\ItemVoteRepository;
use ModelBundle\Repository\ItemCommentRepository;
use ModelBundle\Repository\ItemCommentVoteRepository;

use AppBundle\Services\RankingHelper;

class UpvoteHelper {

    protected $upvote_item_maxage;
    protected $itemrepo;
    protected $itemvoterepo;
    protected $itemcommentrepo;
    protected $itemcommentvoterepo;
    protected $userrepo;
    protected $utils;
    protected $rankinghelper;

    public function __construct(int $upvote_item_maxage, ItemRepository $itemrepo, ItemVoteRepository $itemvoterepo, ItemCommentRepository $itemcommentrepo, ItemCommentVoteRepository $itemcommentvoterepo, UserRepository $userrepo, Utils $utils, RankingHelper $rankinghelper) {
        $this->upvote_item_maxage = $upvote_item_maxage;
        $this->itemrepo = $itemrepo;
        $this->itemvoterepo = $itemvoterepo;
        $this->itemcommentrepo = $itemcommentrepo;
        $this->itemcommentvoterepo = $itemcommentvoterepo;
        $this->userrepo = $userrepo;
        $this->utils = $utils;
        $this->rankinghelper = $rankinghelper;
    }

    public function upvoteItem(User $user, Item $item, string $ipv4 = null) : bool {

        if(!$this->mayUpvoteItem($item, $user)) return false;

        if(is_null($ipv4)) {
            $ipv4 = $this->utils->getIPV4();
        }

        $now = new \DateTime();

        $vote = (new ItemVote())
            ->setUser($user)
            ->setItem($item)
            ->setIPV4($ipv4)
            ->setCreationDate($now);

        $this->itemvoterepo->create($vote);
        $this->itemrepo->incrementPoints($item);
        $this->userrepo->incrementKarma($item->getUser());

        # Updating item momentum is done asynchronously in AppBundle\Command\UpdateItemMomentumCommand

        return true;
    }

    public function upvoteComment(User $user, ItemComment $comment, string $ipv4 = null) {

        if(!$this->mayUpvoteComment($comment, $user)) return false;

        if(is_null($ipv4)) {
            $ipv4 = $this->utils->getIPV4();
        }

        $now = new \DateTime();

        $vote = (new ItemCommentVote())
            ->setUser($user)
            ->setComment($comment)
            ->setItem($comment->getItem())
            ->setIPV4($ipv4)
            ->setCreationDate($now);

        $this->itemcommentvoterepo->create($vote);
        $this->itemcommentrepo->incrementPoints($comment);
        $this->userrepo->incrementKarma($comment->getUser());

        return true;
    }

    public function getEarliestItemVoteDate() : \DateTime {
        return (new \DateTime())->sub(new \DateInterval("PT" . $this->upvote_item_maxage . "H"));
    }

    public function mayUpvoteItem(Item $item, User $user = null) : bool {

        if(is_null($user)) {
            if(!$this->utils->isAuthenticated()) return false;
            $user = $this->utils->getUser();
        }

        if($item->getCreationDate() < $this->getEarliestItemVoteDate()) return false;
        if($user->getId() === $item->getUser()->getId()) return false;

        # Check if user already upvoted
        $previousvote = $this->itemvoterepo->qb_active()
            ->andWhere("data.item = :item")
                ->setParameter("item", $item)
            ->andWhere("data.user = :user")
                ->setParameter("user", $user)
            ->getQuery()
            ->getResult();

        if(count($previousvote) > 0) return false;

        return true;
    }

    public function mayUpvoteComment(ItemComment $comment, User $user = null) : bool {

        if(is_null($user)) {
            if(!$this->utils->isAuthenticated()) return false;
            $user = $this->utils->getUser();
        }

        if($user->getId() === $comment->getUser()->getId()) return false;

        # Check if user already upvoted
        $previousvote = $this->itemcommentvoterepo->qb_active()
            ->andWhere("data.comment = :comment")
                ->setParameter("comment", $comment)
            ->andWhere("data.user = :user")
                ->setParameter("user", $user)
            ->getQuery()
            ->getResult();

        if(count($previousvote) > 0) return false;

        return true;
    }
}