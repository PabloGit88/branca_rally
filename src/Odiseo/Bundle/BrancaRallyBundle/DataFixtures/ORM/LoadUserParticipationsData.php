<?php

namespace Odiseo\Bundle\BrancaRallyBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Odiseo\Bundle\ProjectBundle\DataFixtures\ORM\DataFixture;
use Odiseo\Bundle\BrancaRallyBundle\Entity\User;
use Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;

class LoadUserParticipationsData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
    	$participant1 = new User();
    	$participant1->setUsername('user1');
    	$participant1->setFullname('Usuario A');
    	$participant1->setEmail('usuarioA@example.com');
    	$participant1->setPlainPassword('123456');
    	$participant1->setEnabled(true);
    	$manager->persist($participant1);    	
    	    	
    	$participant2 = new User();
    	$participant2->setUsername('user2');
    	$participant2->setFullname('Usuario B');
    	$participant2->setEmail('usuarioB@example.com');
    	$participant2->setPlainPassword('123456');
    	$participant2->setEnabled(true);
    	$manager->persist($participant2);

    	$race1 = $this->getReference('Race1');
    	
    	$userParticipation1 = new UserRaceParticipation();
    	$userParticipation1->setRace($race1);
    	$userParticipation1->setUser($participant1);
    	$userParticipation1->setSoundFile('example1.aac');
    	$userParticipation1->setTeam(Team::TEAM1);
    	$manager->persist($userParticipation1);
    	
    	
    	$race2 = $this->getReference('Race2');
    	$userParticipation2 = new UserRaceParticipation();
    	$userParticipation2->setRace($race2);
    	$userParticipation2->setUser($participant1);
    	$userParticipation2->setSoundFile('example1.aac');
    	$userParticipation2->setTeam(Team::TEAM2);
    	$manager->persist($userParticipation2);
    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	return 101;
    }
}
