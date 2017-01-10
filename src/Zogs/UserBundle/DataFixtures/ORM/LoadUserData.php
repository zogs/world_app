<?php

namespace Zogs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Zogs\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

	public function load(ObjectManager $manager)
	{		

		$admin = new User();
		$admin->setUsername('admin');
		$admin->setEmail('guichardsim@gmail.com');
		$admin->setEnabled(true);
		$admin->setPlainPassword('pass');
		$admin->setRoles(array('ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_WORLD_ADMIN'));
		//$admin->setLocation($this->getReference('location_dijon'));
		$admin->setConfirmationToken('cazercr6lctetgqael6DnFUjbb8kOV_4_1e88vexlwxSkBZTLw');

		$manager->persist($admin);

		$this->addReference('user_admin',$admin);



		$user1 = new User();
		$user1->setUsername('user1');
		$user1->setEmail('guichardsim+user1@gmail.com');
		$user1->setEnabled(true);
		$user1->setPlainPassword('pass');
		$user1->setRoles(array('ROLE_USER'));
		//$user1->setLocation($this->getReference('location_beaune'));
		$user1->setConfirmationToken('ntybrtv6lctetgqael6DnFUjbb8kOV_4_1e88vexlwxSkBZTLw');

		$manager->persist($user1);

		$this->addReference('user1',$user1);


		$user2 = new User();
		$user2->setUsername('user2');
		$user2->setEmail('guichardsim+user2@gmail.com');
		$user2->setEnabled(true);
		$user2->setPlainPassword('pass');
		$user2->setRoles(array('ROLE_USER'));
		//$user2->setLocation($this->getReference('location_moloy'));
		$user2->setConfirmationToken('sdsbver6lctetgqael6DnFUjbb8kOV_4_1e88vexlwxSkBZTLw');

		$manager->persist($user2);

		$this->addReference('user2',$user2);


		$manager->flush();
	}

	public function getOrder(){

		return 2; // the order in which fixtures will be loaded
	}
}

?>