<?php

namespace Zogs\UserBundle\Service;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

 /**
 * When the user is not authenticated at all (i.e. when the security context has no token yet), 
 * the firewall's entry point will be called to start() the authentication process. 
 */

class LoginEntryPoint implements AuthenticationEntryPointInterface{

     protected $router;

    public function __construct($router){
        $this->router = $router;

    }
     /*
     * This method receives the current Request object and the exception by which the exception 
     * listener was triggered. 
     * 
     * The method should return a Response object
     */

    public function start(Request $request, AuthenticationException $authException = null){
        $session = $request->getSession();

        //I am choosing to set a FlashBag message with my own custom message.
        //Alternatively, you could use AuthenticaionException's generic message 
        //by calling $authException->getMessage()
         $session->getFlashBag()->add('warning', 'Veuillez vous connecter pour accéder à cette page');

        return new RedirectResponse($this->router->generate('fos_user_security_login'));
    }
}