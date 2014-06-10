<?php

namespace asudre\CDM14Bundle\Services;

use asudre\CDM14Bundle\Entity\Equipes;

class ServiceEquipes 
{
	
	/**
	 * MatchsRepository permettant d'effectuer les requetes sur la table Matchs
	 */
	private $equipesRepo;
	
	/**
	 * Constructeur récupérant l'entity manager
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->equipesRepo = $em->getRepository('asudreCDM14Bundle:Equipes');
	}
	
	/**
	 * Récupération de l'objet equipe Nulle
	 * @param unknown $idMatch
	 */
	public function getEquipeNul() {
		return $this->equipesRepo->getEquipe(Equipes::ID_NUL)[0];
	}

}