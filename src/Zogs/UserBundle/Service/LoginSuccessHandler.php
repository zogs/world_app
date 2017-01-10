<?php


namespace Zogs\UserBundle\Service;
 
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
 
use Civc\AppBundle\Manager\PointManager;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	protected $router;
	protected $security;

	public function __construct(Router $router, AuthorizationChecker $authChecker, PointManager $manager)
	{
		$this->router = $router;
		$this->security = $authChecker;
		$this->point_manager = $manager;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		if ($this->security->isGranted('ROLE_SUPER_ADMIN')){
			//return $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));	
		}

		if ($this->security->isGranted('ROLE_USER')){

			//if user has no POI, redirect to the creation page
			if(empty($this->point_manager->getByUser($token->getUser()))) {

				return $response = new RedirectResponse($this->router->generate('civc_app_point_initial'));
			}
			
			return $response = new RedirectResponse($this->router->generate('civc_page_home'));
						
		}

	}
}