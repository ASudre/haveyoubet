<?php

namespace asudre\CreationCDMBundle\Form\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class EquipesCollection
{
	protected $description;
	protected $equipes;
	
	public function __construct()
	{
		$this->equipes = new ArrayCollection();
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getEquipes()
	{
		return $this->equipes;
	}
	
	public function setEquipes(ArrayCollection $equipes)
	{
		$this->equipes = $equipes;
	}
}