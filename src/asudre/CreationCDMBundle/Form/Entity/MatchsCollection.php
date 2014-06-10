<?php

namespace asudre\CreationCDMBundle\Form\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class MatchsCollection
{
	protected $description;
	protected $matchs;
	
	public function __construct()
	{
		$this->matchs = new ArrayCollection();
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getMatchs()
	{
		return $this->matchs;
	}
	
	public function setMatchs(ArrayCollection $matchs)
	{
		$this->matchs = $matchs;
	}
}