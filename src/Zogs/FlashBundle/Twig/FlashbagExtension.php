<?php
 
namespace Zogs\FlashBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;


class FlashbagExtension extends \Twig_Extension
{

	protected $container, $flashbag;

	public function __construct(ContainerInterface $container = null)
	{
		$this->container = $container;
		$this->flashbag = $container->get('flashbag');
	}


	public function renderAll($container = false)
	{
		$notifications = $this->flashbag->all();

		if( count($notifications)>0 ){
			return $this->container->get('templating')->render("ZogsFlashBundle:Notifications:all.html.twig",compact("notifications","container"));
		}
		return null;
	}


	public function getFunctions()
	{
		return array(
		//'flashbag_all' => new \Twig_SimpleFunction($this, 'renderAll', array('is_safe' => array('html')))   
		new \Twig_SimpleFunction('flashbag_all', array($this, 'renderAll')),         
		);
	}


	public function getName()
	{
		return 'zogs_flashbag_extension';
	}


}