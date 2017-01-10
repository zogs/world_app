<?php

namespace Zogs\WorldBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadWorldDatabaseTestData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

	/**
	 * @var ContainerInterface
	*/
	private $container;

	/**
	* {@inheritDoc}
	*/
	public function setContainer(ContainerInterface $container = null)
	{
	 	$this->container = $container;
	}

	public function load(ObjectManager $manager)
	{

		$load = $this->container->getParameter('world.importer.load_fixtures');

		if(true == $load){

			$importer = $this->container->get('world.importer');
			$importer->setMethod('dbal');		
			
			$dir = $this->container->getParameter('world.importer.files_to_import');
			$importer->importAll($dir);
			
		}
		
	}

	public function getOrder(){

		return 0; // the order in which fixtures will be loaded
	}
}

?>