<?php

namespace Zogs\WorldBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Zogs\WorldBundle\Entity\Location;

class LoadLocationTestData extends AbstractFixture implements OrderedFixtureInterface
{

	private $connection;

	public function load(ObjectManager $manager)
	{

		$manager = $this->get('world.orm.manager');

		$this->connection = $manager->getConnection();

		$dijon = $manager->getRepository('ZogsWorldBundle:Location')->findLocationByCityId(2568787);		
		$beaune = $manager->getRepository('ZogsWorldBundle:Location')->findLocationByCityId(2568568);
		$moloy = $manager->getRepository('ZogsWorldBundle:Location')->findLocationByCityId(2569058);	

		$manager->persist($dijon);
		$manager->persist($beaune);
		$manager->persist($moloy);
		
		$this->addReference('location_dijon', $dijon);		
		$this->addReference('location_beaune', $beaune);		
		$this->addReference('location_moloy', $moloy);

		$this->connection->executeUpdate("SET FOREIGN_KEY_CHECKS=0;");

		$manager->flush();

		$this->connection->executeUpdate("SET FOREIGN_KEY_CHECKS=1;"); 
		
		

	}

	public function getOrder(){

		return 1; // the order in which fixtures will be loaded
	}
}

?>