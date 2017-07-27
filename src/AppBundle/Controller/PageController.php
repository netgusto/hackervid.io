<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller {

    public function guidelinesAction(int $page = 1) {
        return $this->render("AppBundle:Page:guidelines.html.twig");
    }

    public function contactAction(int $page = 1) {
        return $this->render("AppBundle:Page:contact.html.twig");
    }

}