<?php

namespace AppBundle\Services;

use Symfony\Component\Routing\RouterInterface;

class URLBuilder {

    protected $router;

    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    public function home() : string {
        return $this->router->generate('app_home');
    }

    public function guidelines() : string {
        return $this->router->generate('app_guidelines');
    }

    public function contact() : string {
        return $this->router->generate('app_contact');
    }

    public function login() : string {
        return $this->router->generate('app_login');
    }

    public function logincheck() : string {
        return $this->router->generate('app_login_check');
    }

    public function logout() : string {
        return $this->router->generate('app_logout');
    }

    public function item_topranking(int $page = 1) : string {
        $params = [];
        if($page > 1) {
            $params['page'] = $page;
        }

        return $this->router->generate('app_item_topranking', $params);
    }

    public function item_newest() : string {
        return $this->router->generate('app_item_newest');
    }

    public function item_submit() : string {
        return $this->router->generate('app_item_submit');
    }

    public function item_detail(int $id) : string {
        return $this->router->generate('app_item_detail', ['id' => $id]);
    }

    public function item_upvote(int $id, string $goto = null) : string {
        return $this->router->generate('app_item_upvote', ['id' => $id, 'goto' => $goto]);
    }

    public function item_comment(int $id, int $commentid) : string {
        return $this->router->generate('app_item_comment', ['id' => $id, 'commentid' => $commentid]);
    }

    public function item_comment_upvote(int $id, string $goto = null) : string {
        return $this->router->generate('app_item_comment_upvote', ['id' => $id, 'goto' => $goto]);
    }

    public function user_account() : string {
        return $this->router->generate('app_user_account');
    }

    public function user_profile(string $username) : string {
        return $this->router->generate('app_user_profile', ['username' => $username]);
    }
}