<?php

namespace Zogs\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Zogs\UtilsBundle\Utils\String;
/**
 * SettingsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function save(User $user)
	{
		$this->_em->persist($user);
		$this->_em->flush();
		return $user;
	}
	public function countAll()
	{
		return $this->createQueryBuilder('u')
				 ->select('COUNT(u)')
				 ->getQuery()
				 ->getSingleScalarResult();
	}

	public function generateNewConfirmationToken(User $user)
	{
		$token = String::randomHash(120);
		$user->setConfirmationToken($token);

		$this->_em->persist($user);
		$this->_em->flush();

		return $user;
	}

	public function findRegistrationLastFewDays($days) {

		$qb = $this->createQueryBuilder('u');

		$qb->select('u')
		->where($qb->expr()->lte('u.register_since','CURRENT_DATE()'))
		->andWhere($qb->expr()->gte('u.register_since',"DATE_SUB(CURRENT_DATE(), $days, 'day')"))
		;

		return $qb->getQuery()->getResult();
	}
}
