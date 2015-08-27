<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Entity;

use DateTime;
use Odiseo\Bundle\ProjectBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User extends BaseUser
{    
	protected $facebookUserId;
	protected $facebookAccessToken;
	protected $name;
	protected $lastname;
	protected $phone;
	protected $participations;
	
    public function __construct()
    {
    	parent::__construct();
    	
    	$this->participations = new ArrayCollection();
    }
    
    public function setFacebookUserId($facebookUserId)
    {
    	$this->facebookUserId = $facebookUserId;
    }
    
    public function getFacebookUserId()
    {
    	return $this->facebookUserId;
    }    
    
    public function setFacebookAccessToken($facebookAccessToken)
    {
    	$this->facebookAccessToken = $facebookAccessToken;
    }
    
    public function getFacebookAccessToken()
    {
    	return $this->facebookAccessToken;
    }
    
    public function setParticipations($participations)
    {
    	$this->participations = $participations;
    }
    
    public function getParticipations()
    {
    	return $this->participations;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setLastName($lastname)
    {
    	$this->lastname = $lastname;
    }
    
    public function getLastName()
    {
    	return $this->lastname;
    }
    
    public function setPhone($phone)
    {
    	$this->phone = $phone;
    }
    
    public function getPhone()
    {
    	return $this->phone;
    }
}