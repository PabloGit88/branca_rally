<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Entity;

use DateTime;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;

/**
 * User
 */
class UserRaceParticipation
{	
    protected $id;
    
    protected $user;
    protected $race;
    protected $team;
    protected $soundFile;
    protected $soundDuration;
    protected $isFacebookShared;
    
    protected $createdAt;
    protected $updatedAt;
    
    public function __construct()
    {
    	$this->createdAt = new DateTime('now');
    }

    public function getId()
    {
    	return $this->id;
    }
    public function setUser(User $user)
    {
    	$this->user = $user;
    
    	return $this;
    }
    
    public function getUser()
    {
    	return $this->user;
    }
    
    public function setRace(Race $race)
    {
    	$this->race = $race;
    
    	return $this;
    }
    
    public function getRace()
    {
    	return $this->race;
    }
    
    public function setTeam($team)
    {
    	$this->team = $team;
    
    	return $this;
    }
    
    public function getTeam()
    {
    	return $this->team;
    }
    
    public function getTeamName()
    {
    	return Team::getTeamName($this->team);
    }
    
    public function setSoundFile($soundFile)
    {
    	$this->soundFile = $soundFile;
    
    	return $this;
    }
    
    public function getSoundFile()
    {
    	return $this->soundFile;
    }
    
    public function setSoundDuration($soundDuration)
    {
    	$this->soundDuration = $soundDuration;
    
    	return $this;
    }
    
    public function getSoundDuration()
    {
    	return $this->soundDuration;
    }

    public function getSoundFileBasename()
    {
    	$pathinfo = pathinfo($this->soundFile);
    	return isset($pathinfo['filename'])?$pathinfo['filename']:'';
    }
    
    public function setIsFacebookShared($isFacebookShared)
    {
    	$this->isFacebookShared = $isFacebookShared;
    
    	return $this;
    }
    
    public function getIsFacebookShared()
    {
    	return $this->isFacebookShared;
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
    
    public function __toString(){
    	return $this->team;
    }
}