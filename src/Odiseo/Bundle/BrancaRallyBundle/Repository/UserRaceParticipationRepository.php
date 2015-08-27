<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class UserRaceParticipationRepository extends EntityRepository
{   
    public function getRegisteredUsers()
    {
    	$queryBuilder = $this->getCollectionQueryBuilder();
    	return $queryBuilder->getQuery()->getResult();
    }
    
    
 public function findUserSound( $user , $raceId)
    {	
    	$queryBuilder = $this->createQueryBuilder($this->getAlias());
    	$queryBuilder->select($this->getAlias().'.soundFile ');
     	$queryBuilder->where($this->getAlias().'.user = :user ' );
     	$queryBuilder->andWhere($this->getAlias().'.race = :race_id ');
     	$queryBuilder->setParameter('user', $user);
     	$queryBuilder->setParameter('race_id', $raceId);
    	return $queryBuilder->getQuery()->getResult()[0]['soundFile'];
    }
    
}