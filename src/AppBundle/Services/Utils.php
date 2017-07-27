<?php

namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;

class Utils {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function errorsToStringArray(ConstraintViolationListInterface $errors) : array {
        return array_map(function(ConstraintViolationInterface $violation) : string {
            return $violation->getMessage();
        }, (array)$errors->getIterator());
    }

    public function formErrorsToStringArray(Form $form) : array {

        $errors = [];

        foreach($form->getErrors(true, false) as $error) {
            if($error instanceof FormErrorIterator) {
                $errors[] = $error->current()->getMessage();
            } else if($error instanceof FormError) {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }

    public function getIPV4() {
        $requeststack = $this->container->get('request_stack');
        return $requeststack->getMasterRequest()->getClientIp();
    }

    public function isAuthenticated() : bool {
        $tokenstorage = $this->container->get('security.token_storage');

        $token = $tokenstorage->getToken();
        return !is_null($token) && $token->isAuthenticated() && !is_string($token->getUser());
    }

    public function getUser() {
        $tokenstorage = $this->container->get('security.token_storage');

        if(!$this->isAuthenticated()) return null;
        return $tokenstorage->getToken()->getUser();
    }

    public function pageToRange(int $page, int $perpage = 5) : array {
        return [
            'start' => ($page-1) * $perpage,
            'number' => $perpage,
            'end' => ($page * $perpage) - 1,
        ];
    }

    public function extractDomain(string $url) : string {
        $domain = mb_strtolower(parse_url($url, PHP_URL_HOST));
        if(mb_substr($domain, 0, 4) === "www." && mb_strlen($domain) > 4) {
            $domain = mb_substr($domain, 4);
        }

        return $domain;
    }

    public function getRequestInfo() : array {
        $requeststack = $this->container->get('request_stack');

        $target = $requeststack->getMasterRequest()->attributes->get('_controller');
        $parts = explode('::', $target);

        // normalizing
        if(count($parts) === 1) {
            # ex: AppBundle:Default:index
            $parts = explode(':', $target);
            $controller = $parts[0] . '\\Controller\\' . $parts[1] . 'Controller';
            $action = $parts[2] . 'Action';
            $target = $controller . '::' . $action;
        } else {
            $controller = $parts[0];
            $action = $parts[1];
        }

        // rewriting
        $controllerParts = explode('\\', $controller);
        $controllerNameClean = preg_replace('%Controller$%', '', $controllerParts[2]);
        $actionClean = preg_replace('%Action$%', '', $action);

        $controllerClean = $controllerParts[0] . ':' . $controllerNameClean;

        return ['controller' => $controllerClean, 'method' => $actionClean, 'action' => $controllerClean . ':' . $actionClean];
    }

    public function getAgeHours(\DateTime $dt, \DateTime $ref = null) : int {
        if(is_null($ref)) $ref = new \DateTime();

        $diff = $dt->diff($ref);
        return ($diff->days * 24) + $diff->h;
    }
}