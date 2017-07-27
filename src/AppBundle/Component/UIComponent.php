<?php

namespace AppBundle\Component;

use Twig_Environment;

class UIComponent {

    protected $twig;

    public function __construct(Twig_Environment $twig) {
        $this->twig = $twig;
    }

    public function headernav(array $props) : string {
        $props = [
            'actives' => $props['actives'],
            'isauth' => $props['isauth'],
            'username' => $props['username'],
            'karma' => $props['karma'],
            'url_new' => $props['url_new'],
            'url_submit' => $props['url_submit'],
            'url_account' => $props['url_account'],
            'url_login' => $props['url_login'],
            'url_logout' => $props['url_logout'],
        ];

        return $this->twig->render('AppBundle:Component:UI/headernav.html.twig', $props);
    }

    public function footernav(array $props) : string {
        $props = [
            'url_guidelines' => $props['url_guidelines'],
            'url_contact' => $props['url_contact'],
        ];

        return $this->twig->render('AppBundle:Component:UI/footernav.html.twig', $props);
    }

    public function item(array $props) : string {
        $props = [
            'upvote' => array_key_exists('upvote', $props) ? !!$props['upvote'] : true,
            'url_upvote' => $props['url_upvote'],
            'title' => $props['title'],
            'url' => $props['url'],
            'username' => $props['username'],
            'points' => intval($props['points']),
            'url_user' => $props['url_user'],
            'url_item' => $props['url_item'],
            'nbcomments' => intval($props['nbcomments']),
            'reldate' => $props['reldate'],
            'domain' => $props['domain'],
        ];

        return $this->twig->render('AppBundle:Component:UI/item.html.twig', $props);
    }

    public function itemlistordered(array $props) : string {
        $props = [
            'items' => $props['items'],
            'startindex' => $props['startindex'],
            'hasmore' => array_key_exists('hasmore', $props) ? $props['hasmore'] : false,
            'url_more' => array_key_exists('url_more', $props) ? $props['url_more'] : null,
        ];

        return $this->twig->render('AppBundle:Component:UI/itemlistordered.html.twig', $props);
    }

    public function comment(array $props, bool $mentionitem = false) : string {
        $props['mentionitem'] = $mentionitem;
        $props['reply'] = !$mentionitem;
        return $this->twig->render('AppBundle:Component:UI/comment.html.twig', $props);
    }

    public function formerrors(array $props = []) : string {
        $props = [
            'errors' => $props['errors'],
        ];

        return $this->twig->render('AppBundle:Component:UI/formerror.html.twig', $props);
    }

    public function commenttree(array $props) : string {
        $props = [
            'comments' => $props['comments'],
        ];

        return $this->twig->render('AppBundle:Component:UI/commenttree.html.twig', $props);
    }

    public function upvote(array $props) : string {
        $props = [
            'url' => $props['url'],
            'small' => array_key_exists('small', $props) ? $props['small'] : false,
        ];

        return $this->twig->render('AppBundle:Component:UI/upvote.html.twig', $props);
    }
}
