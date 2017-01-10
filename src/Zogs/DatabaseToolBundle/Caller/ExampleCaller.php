<?php

namespace Zogs\DatabaseToolBundle\Caller;

use Zogs\DatabaseToolBundle\Caller\AbstractCaller;

class ExampleCaller extends AbstractCaller
{
	
	public function setType()
	{
		$db = $this->container->get('doctrine.dbal.oldwesport_connection');
		$stmt = $db->prepare('SELECT * FROM users WHERE user_id='.$this->entry['user_id'].' LIMIT 1');
		$stmt->execute();
		$user = $stmt->fetch();

		if(empty($user)) return 'person';
		if($user['account']=='public') return 'person';
		if($user['account']=='asso') return 'asso';
		if($user['account']=='bizness') return 'pro';
		return 'person';

	}

	public function setOrganizer()
	{

		$user = $this->em->getRepository('MyUserBundle:User')->findOneById($this->entry['user_id']);

		if(NULL===$user) return '_skip_';

		return $user;
	}
	

	public function setSport()
	{
		$sport = $this->em->getRepository('WsSportsBundle:Sport')->findOneBySlug($this->entry['sport']);

		return $sport;
	}

	public function setOccurence()
	{
		return $this->entity->getSerie()->getOccurences();
	}

	public function setSpot($location_fields)
	{		

		$spot = new \Ws\EventsBundle\Entity\Spot();

		$spot->setAddress($this->entry['address']);
		
		$location = $this->findLocationFromData($location_fields);
		if($location === null) return '_skip_';
		if($location->hasCity() === false) return '_skip_';
		
		$spot->setLocation($location);

		//check if the slug already exist in the database, and return the existing spot if true
		$slug = $spot->createSlug();
		if(null !== $existing_spot = $this->em->getRepository('WsEventsBundle:Spot')->findOneBySlug($slug)){
			return $existing_spot;
		}

		return $spot;
	}

	public function setLocation()
	{
		if($this->entity->getSpot() !== null && $this->entity->getSpot()->getLocation() !== null){
			return $this->entity->getSpot()->getLocation();
		}

		return '_skip_';
	}
}