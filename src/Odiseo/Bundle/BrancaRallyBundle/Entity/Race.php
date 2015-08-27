<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Entity;

use DateTime;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;

/**
 * User
 */
class Race
{
    protected $id;
    
    protected $name;
    protected $isActive;
    protected $startDate;
    protected $endDate;
    protected $participations;
    
    protected $createdAt;
    protected $updatedAt;
    
    public function __construct()
    {
    	$this->participations = new ArrayCollection();
    	$this->createdAt = new DateTime('now');
    }

    public function getId()
    {
    	return $this->id;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    
    	return $this;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setIsActive($isActive)
    {
    	$this->isActive = $isActive;
    
    	return $this;
    }
    
    public function getIsActive()
    {
    	return $this->isActive;
    }
    
    public function setStartDate($startDate)
    {
    	$this->startDate = $startDate;
    
    	return $this;
    }
    
    public function getStartDate()
    {
    	return $this->startDate;
    }
    
    public function setEndDate($endDate)
    {
    	$this->endDate = $endDate;
    
    	return $this;
    }
    
    public function getEndDate()
    {
    	return $this->endDate;
    }
    
    public function isStarted()
    {
    	return (bool) (strtotime("now") > $this->getStartDate()->format('U'));
    }
    
    public function isEnded()
    {
    	return (bool) (strtotime("now") > $this->getEndDate()->format('U'));
    }
    
    public function canShowWinners()
    {
    	return (bool) (strtotime("now + 30 minutes") > $this->getEndDate()->format('U'));
    }
    
    public function setParticipations($participations)
    {
    	$this->participations = $participations;
    }
    
    public function getParticipations()
    {
    	return $this->participations;
    }
    
    public function getParticipationsOnTeam($team)
    {
    	$teamParticipations = array();
    	foreach ($this->getParticipations() as $participation)
    	{
    		if($participation->getTeam() == $team) $teamParticipations[] = $participation;
    	}
    	
    	return $teamParticipations;
    }
    
    public function countParticipationsOnTeam($team)
    {
    	return count($this->getParticipationsOnTeam($team));
    }
    
    public function countSoundsDurationOnTeam($team)
    {
    	$totalDuration = 0;
    	foreach ($this->getParticipationsOnTeam($team) as $participation)
    	{
    		$duration = $participation->getSoundDuration();
    		if($duration)
    		{
    			$totalDuration += $duration;
    		}
    	}
    	
    	return $totalDuration;
    }
    
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function __toString()
    {
    	return $this->getName();
    }
    
    public function getTeamWinner($race)
    {	
    	$countTeam1 = 0;
    	$countTeam2 = 0;
    	$teamWinner='';
    	$participations = $race->getParticipations();
    	foreach($participations as $userParticipation)
    	{
    		if($userParticipation->getTeam() == Team::TEAM1)
    		{
    			$countTeam1++;
    		}else if($userParticipation->getTeam() == Team::TEAM2)
    		{
    			$countTeam2++;
    		}
    	}
    	
    	if($countTeam1 > $countTeam2)
    	{
    		$teamWinner = Team::TEAM1;
    	}else{
    		$teamWinner = Team::TEAM2;
    	}
    	
    	return $teamWinner;
    }
}