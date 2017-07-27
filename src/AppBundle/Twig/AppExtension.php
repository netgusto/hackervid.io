<?php

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

use ModelBundle\Entity\EnvoiMediazeen;

class AppExtension extends Twig_Extension {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getName() : string {
        return 'app.twigextension';
    }

    public function getFunctions() : array {
        return array(
            'component' => new Twig_SimpleFunction('component', array($this, 'component'), array('is_safe' => array('html'))),
            'headernav' => new Twig_SimpleFunction('headernav', array($this, 'headernav'), array('is_safe' => array('html'))),
            'footernav' => new Twig_SimpleFunction('footernav', array($this, 'footernav'), array('is_safe' => array('html'))),
            'isauthenticated' => new Twig_SimpleFunction('isauthenticated', array($this, 'isauthenticated'), array('is_safe' => array('html'))),
            'getuser' => new Twig_SimpleFunction('getuser', array($this, 'getuser'), array('is_safe' => array('html'))),
            'relativedate' => new Twig_SimpleFunction('relativedate', array($this, 'relativedate'), array('is_safe' => array('html'))),
            'titlecase' => new Twig_SimpleFunction('titlecase', array($this, 'titlecase'), array('is_safe' => array('html'))),
        );
    }

    public function component(string $name) {
        return $this->container->get('component.' . $name);
    }

    public function headernav() : string {

        $utils = $this->container->get('utils');
        $urlbuilder = $this->container->get('urlbuilder');
        $requestinfo = $utils->getRequestInfo();

        $username = null;
        $karma = null;

        $user = $this->getUser();
        $isauth = !is_null($user);
        if($isauth) {
            $username = $user->getUsername();
            $karma = $user->getKarma();
        }

        $url_login = $urlbuilder->login();

        $actives = [
            'topranking' => $requestinfo['action'] === 'AppBundle:Item:topranking',
            'newest' => $requestinfo['action'] === 'AppBundle:Item:newest',
            'submit' => $requestinfo['action'] === 'AppBundle:Item:submit',
            'login' => $requestinfo['action'] === 'AppBundle:User:loginRegister',
            'profile' => $requestinfo['controller'] === 'AppBundle:User' && in_array($requestinfo['method'], ['account', 'profile']),
        ];

        return $this->component('ui')->headernav([
            'actives' => $actives,
            'isauth' => $isauth,
            'username' => $username,
            'karma' => $karma,
            'url_new' => $urlbuilder->item_newest(),
            'url_submit' => $urlbuilder->item_submit(),
            'url_account' => $urlbuilder->user_account(),
            'url_login' => $urlbuilder->login(),
            'url_logout' => $urlbuilder->logout(),
        ]);
    }

    public function isAuthenticated() : bool {
        $utils = $this->container->get('utils');
        return $utils->isAuthenticated();
    }

    public function getUser() {
        $utils = $this->container->get('utils');
        return $utils->getUser();
    }

    public function footernav() : string {
        $urlbuilder = $this->container->get('urlbuilder');
        return $this->component('ui')->footernav([
            'url_guidelines' => $urlbuilder->guidelines(),
            'url_contact' => $urlbuilder->contact(),
        ]);
    }

    public function relativedate() : string {
        return '18 days ago';
    }

    public function titlecase(string $title) : string {
        return ucwords($title);
    }
}
