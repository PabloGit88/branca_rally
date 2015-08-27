<?php

namespace Odiseo\Bundle\BrancaRallyBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Odiseo\Bundle\BrancaRallyBundle\Entity\User;
use Odiseo\Bundle\BrancaRallyBundle\Entity\Race;
use Odiseo\Bundle\ProjectBundle\DataFixtures\ORM\DataFixture;

class LoadRacesData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
    	/** Races **/
    	$race1 = new Race();
    	$race1->setName("Carrera 1");
    	$race1->setIsActive(true);
    	$race1->setStartDate(new \DateTime("+1 week"));
    	$race1->setEndDate(new \DateTime("+2 week"));
    	$manager->persist($race1);
    	$this->setReference('Race1', $race1);
    	
    	    	
    	$race2 = new Race();
    	$race2->setName("Carrera 2");
    	$race2->setIsActive(false);
    	$race2->setStartDate(new \DateTime("+3 week"));
    	$race2->setEndDate(new \DateTime("+4 week"));
    	$manager->persist($race2);
    	$this->setReference('Race2', $race2);
    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	return 100;
    }
}