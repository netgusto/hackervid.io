<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\UserType;
use ModelBundle\Entity\User;

class UserController extends Controller {

    public function loginRegisterAction(Request $request, AuthenticationUtils $authUtils) : Response {

        $urlbuilder = $this->get('urlbuilder');
        $formfactory = $this->get('form.factory');
        $passwordencoder = $this->get('security.password_encoder');
        $userrepo = $this->get('userrepo');
        $tokenstorage = $this->get('security.token_storage');
        $session = $this->get('session');
        $utils = $this->get('utils');

        $newuser = (new User())
            ->setCreationDate(new \DateTime())
            ->setKarma(1);

        $form = $formfactory->create(UserType::class, $newuser, array(
            'validation_groups' => ['create'],
        ));

        $form->handleRequest($request);
        $createerrors = [];

        if ($form->isSubmitted()) {
            if($form->isValid()) {
                $newuser->setPasswordHash($passwordencoder->encodePassword($newuser, $newuser->plainpassword));
                $newuser->setUsername(strtolower($newuser->getUsername()));
                $createerrors = $userrepo->create($newuser);

                if(empty($errors)) {
                    // automatic login
                    $token = new UsernamePasswordToken($newuser, null, 'main', $newuser->getRoles());
                    $tokenstorage->setToken($token);
                    $session->set('_security_main', serialize($token));

                    // redirect to home
                    return new RedirectResponse($urlbuilder->home());
                }
            } else {
                $createerrors = $utils->formErrorsToStringArray($form);
            }
        } else {
            // login automatically handled by Symfony
        }

        return $this->render('AppBundle:User:loginregister.html.twig', array(
            'login' => [
                'url_login' => $urlbuilder->logincheck(),
                'last_username' => $authUtils->getLastUsername(),
                'error' => $authUtils->getLastAuthenticationError(),
            ],
            'create' => [
                'form' => $form->createView(),
                'errors' => $createerrors,
            ]
        ));
    }

    public function accountAction() : Response {
        $utils = $this->get("utils");
        return $this->forward("AppBundle:User:profile", ["username" => $utils->getUser()->getUsername()]);
    }

    public function profileAction(string $username) {
        $userrepo = $this->get('userrepo');
        $relativedatehelper = $this->get('relativedatehelper');

        $user = $userrepo->findOneActiveByUsername($username);
        if(is_null($user)) throw $this->createNotFoundException("This user does not exist.");

        return $this->render("AppBundle:User:profile.html.twig", [
            'username' => $user->getUsername(),
            'creationdate' => $user->getCreationDate(),
            'reldate' => $relativedatehelper->get($user->getCreationDate()),
            'karma' => $user->getKarma(),
        ]);
    }

}