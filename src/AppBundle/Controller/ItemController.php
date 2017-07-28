<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Form\ItemType;
use AppBundle\Form\ItemCommentType;

use ModelBundle\Entity\Item;
use ModelBundle\Entity\ItemVote;
use ModelBundle\Entity\ItemComment;
use ModelBundle\Entity\ItemCommentVote;

class ItemController extends Controller {

    protected function itemToViewProps(Item $item, bool $upvote, string $nexturl = null) : array {
        $relativedatehelper = $this->get('relativedatehelper');
        $urlbuilder = $this->get('urlbuilder');
        $utils = $this->get('utils');

        $user = $item->getUser();

        return [
            'upvote' => $upvote,
            'url_upvote' => $urlbuilder->item_upvote($item->getId(), $nexturl),
            'title' => $item->getTitle(),
            'url' => $item->getURL(),
            'points' => $item->getPoints(),
            'username' => $user->getUsername(),
            'url_user' => $urlbuilder->user_profile($user->getUsername()),
            'url_item' => $urlbuilder->item_detail($item->getId()),
            'reldate' => $relativedatehelper->get($item->getCreationDate()),
            'domain' => $utils->extractDomain($item->getURL()),
            'nbcomments' => count($item->getComments()),
        ];
    }

    protected function commentToViewProps(ItemComment $comment, bool $upvote, string $nexturl = null) : array {
        $urlbuilder = $this->get('urlbuilder');
        $relativedatehelper = $this->get('relativedatehelper');
        $formatdochelper = $this->get('formatdochelper');

        $item = $comment->getItem();
        $itemtitle = ucwords($item->getTitle());
        $itemurl = $urlbuilder->item_detail($item->getId());

        $parent = $comment->getParent();
        $user = $comment->getUser();

        return [
            'upvote' => $upvote,
            'url_upvote' => $urlbuilder->item_comment_upvote($comment->getId(), $nexturl),
            'id' => $comment->getId(),
            'content' => $formatdochelper->toHtml($comment->getContent()),
            'username' => $user->getUsername(),
            'itemtitle' => $itemtitle,
            'reldate' => $relativedatehelper->get($comment->getCreationDate()),
            'url_item' => $itemurl,
            'url_user' => $urlbuilder->user_profile($user->getUsername()),
            'url_reply' => $urlbuilder->item_comment($item->getId(), $comment->getId()),
            'url_parent' => is_null($parent) ? $itemurl : $urlbuilder->item_comment($item->getId(), $parent->getId()),
        ];
    }

    protected function itemList(int $page, string $template, callable $fetch, callable $pageurl) {
        $itemvoterepo = $this->get("itemvoterepo");
        $urlbuilder = $this->get("urlbuilder");
        $utils = $this->get("utils");
        $upvotehelper = $this->get("upvotehelper");

        $user = $utils->getUser();

        # TopRanking only displays the items young enough to be voted for
        $earliestdate = $upvotehelper->getEarliestItemVoteDate();

        $fetchRange = $utils->pageToRange($page);
        $items = $fetch(
            $fetchRange['start'],
            $fetchRange['number'] + 1,  # +1 to check if there's a next page
            $earliestdate
        );

        $hasmore = (count($items) === $fetchRange['number'] + 1);
        if($hasmore) {
            array_pop($items);
        }

        $upvotedids = [];
        if(!is_null($user)) {
            # Fetching user upvotes to hide upvote arrows on already upvoted items
            $upvotedids = array_map(function(ItemVote $vote) : int {
                return $vote->getItem()->getId();
            }, $itemvoterepo->qb_active()
                ->andWhere("data.user = :user")
                    ->setParameter("user", $user)
                ->andWhere("data.creationdate >= :earliestdate")
                    ->setParameter("earliestdate", $earliestdate)
                ->getQuery()
                ->getResult()
            );
        }

        $thispageurl = $pageurl($page);

        return $this->render("AppBundle:Item:topranking.html.twig", [
            'startindex' => $fetchRange['start'] + 1,
            'hasmore' => $hasmore,
            'url_more' => $pageurl($page+1),
            'items' => array_map(function(Item $item) use(&$upvotedids, &$pageurl, $thispageurl, &$user) : array {
                return $this->itemToViewProps(
                    $item,
                    is_null($user) || ($user->getId() !== $item->getUser()->getId() && !in_array($item->getId(), $upvotedids)),
                    $thispageurl
                );
            }, $items),
        ]);
    }

    public function topRankingAction(int $page = 1) {

        $itemrepo = $this->get("itemrepo");
        $urlbuilder = $this->get("urlbuilder");

        $fetch = function(int $start, int $number, \DateTime $earliestdate) use(&$itemrepo) : array {
            return $itemrepo->fetchTopRankingRange(
                $start,
                $number,
                $earliestdate
            );
        };

        $pageurl = function(int $page) use(&$urlbuilder) : string {
            return $urlbuilder->item_topranking($page);
        };

        return $this->itemList($page, "AppBundle:Item:topranking.html.twig", $fetch, $pageurl);
    }

    public function newestAction(int $page = 1) {

        $itemrepo = $this->get("itemrepo");
        $urlbuilder = $this->get("urlbuilder");

        $fetch = function(int $start, int $number, \DateTime $earliestdate) use(&$itemrepo) : array {
            return $itemrepo->fetchNewestRange(
                $start,
                $number,
                $earliestdate
            );
        };

        $pageurl = function(int $page) use(&$urlbuilder) : string {
            return $urlbuilder->item_newest($page);
        };

        return $this->itemList($page, "AppBundle:Item:topranking.html.twig", $fetch, $pageurl);
    }

    public function submitAction(Request $request, int $page = 1) {
        $formfactory = $this->get('form.factory');
        $utils = $this->get('utils');
        $itemrepo = $this->get('itemrepo');
        $urlbuilder = $this->get('urlbuilder');

        $item = (new Item())
            ->setCreationDate(new \DateTime())
            ->setUser($utils->getUser())
            ->setIPV4($utils->getIPV4())
            ->setPoints(1)
            ->setMomentum(0);

        $form = $formfactory->create(ItemType::class, $item, array(
            'validation_groups' => ['create'],
        ));

        $errors = [];

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($form->isValid()) {
                $errors = $itemrepo->create($item);
                if(empty($errors)) {
                    // redirect to newest
                    return new RedirectResponse($urlbuilder->item_newest());
                }
            } else {
                $errors = $utils->formErrorsToStringArray($form);
            }
        }

        return $this->render("AppBundle:Item:submit.html.twig", [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }

    public function detailAction(Request $request, int $id, int $commentid = null) : Response {

        $itemrepo = $this->get('itemrepo');
        $itemcommentrepo = $this->get('itemcommentrepo');
        $itemcommentvoterepo = $this->get('itemcommentvoterepo');
        $formfactory = $this->get('form.factory');
        $utils = $this->get('utils');
        $urlbuilder = $this->get('urlbuilder');
        $commenttreehelper = $this->get('commenttreehelper');
        $upvotehelper = $this->get('upvotehelper');

        $item = $itemrepo->findOneActiveById($id);
        if(is_null($item)) throw $this->createNotFoundException("This item does not exist.");

        $parentcomment = null;
        if(!is_null($commentid)) {
            $parentcomment = $itemcommentrepo->findOneActiveById($commentid);
            if(is_null($parentcomment)) throw $this->createNotFoundException("This comment does not exist.");
            if($parentcomment->getItem()->getId() !== $item->getId()) throw $this->createNotFoundException("This comment is not attached to this item.");
        }

        $errors = [];
        $commentform = null;
        $isauth = $utils->isAuthenticated();
        $user = null;

        if($isauth) {
            $user = $utils->getUser();

            $comment = (new ItemComment())
                ->setItem($item)
                ->setUser($user)
                ->setParent($parentcomment)
                ->setPoints(1)
                ->setIPV4($utils->getIPV4())
                ->setCreationDate(new \DateTime());

            $commentform = $formfactory->create(ItemCommentType::class, $comment, array(
                'validation_groups' => ['create'],
            ));

            $commentform->handleRequest($request);
            if($commentform->isSubmitted()) {
                if($commentform->isValid()) {
                    $errors = $itemcommentrepo->create($comment);
                    if(empty($errors)) {
                        return new RedirectResponse($urlbuilder->item_detail($item->getId()) . "#comm-" . $comment->getId());
                    }
                } else {
                    $errors = $utils->formErrorsToStringArray($commentform);
                }
            }
        }

        $mayUpvoteItem = $upvotehelper->mayUpvoteItem($item, $user);

        $upvotedCommentsIds = [];
        if(!is_null($user)) {
            # Fetching user upvotes to hide upvote arrows on already upvoted items
            $upvotedCommentsIds = array_map(function(ItemCommentVote $vote) : int {
                return $vote->getComment()->getId();
            }, $itemcommentvoterepo->qb_active()
                ->andWhere("data.user = :user")
                    ->setParameter("user", $user)
                ->andWhere("data.item = :item")
                    ->setParameter("item", $item)
                ->getQuery()
                ->getResult()
            );
        }

        $itemurl = $urlbuilder->item_detail($item->getId());

        return $this->render("AppBundle:Item:detail.html.twig", [
            'isauth' => $isauth,
            'item' => $this->itemToViewProps($item, $mayUpvoteItem),
            'url_login' => $urlbuilder->login(),
            'commentform' => !is_null($commentform) ? $commentform->createView() : null,
            'commentformerrors' => $errors,
            'commenttree' => $commenttreehelper->getCommentTreeForItem($item, function(ItemComment $comment) use(&$upvotedCommentsIds, $itemurl, &$user) {
                return $this->commentToViewProps(
                    $comment,
                    is_null($user) || ($user->getId() !== $comment->getUser()->getId() && !in_array($comment->getId(), $upvotedCommentsIds)),
                    $itemurl
                );
            }, is_null($parentcomment) ? null : $parentcomment->getId()),
            'parentcomment' => is_null($parentcomment) ? null : $this->commentToViewProps(
                $parentcomment,
                $upvotehelper->mayUpvoteComment($parentcomment, $user),
                $itemurl
            ),
        ]);
    }

    public function upvoteAction(Request $request, int $id) : Response {

        $urlbuilder = $this->get('urlbuilder');
        $utils = $this->get('utils');
        $upvotehelper = $this->get('upvotehelper');
        $itemrepo = $this->get('itemrepo');

        $user = $utils->getUser();
        if(is_null($user)) return new RedirectResponse($urlbuilder->login());

        $item = $itemrepo->findOneActiveById($id);
        if(is_null($item)) throw $this->createNotFoundException("This item does not exist.");

        $upvotehelper->upvoteItem($user, $item);

        $goto = $request->query->has("goto") ? $request->query->get("goto") : null;
        if(is_null($goto)) {
            $goto = $urlbuilder->home();
        }

        return new RedirectResponse($goto);
    }

    public function commentUpvoteAction(Request $request, int $id) : Response {

        $urlbuilder = $this->get('urlbuilder');
        $utils = $this->get('utils');
        $upvotehelper = $this->get('upvotehelper');
        $commentrepo = $this->get('itemcommentrepo');

        $user = $utils->getUser();
        if(is_null($user)) return new RedirectResponse($urlbuilder->login());

        $comment = $commentrepo->findOneActiveById($id);
        if(is_null($comment)) throw $this->createNotFoundException("This comment does not exist.");

        $upvotehelper->upvoteComment($user, $comment);

        $goto = $request->query->has("goto") ? $request->query->get("goto") : null;
        if(is_null($goto)) {
            $goto = $urlbuilder->home();
        }

        return new RedirectResponse($goto);
    }
}