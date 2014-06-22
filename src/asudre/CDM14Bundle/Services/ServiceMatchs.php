<?php

namespace asudre\CDM14Bundle\Services;

use asudre\CDM14Bundle\Entity\Mises;
use asudre\CDM14Bundle\Entity\Matchs;

class ServiceMatchs 
{
	
	/**
	 * MatchsRepository permettant d'effectuer les requetes sur la table Matchs
	 */
	private $matchsRepo;
	
	/**
	 * Constructeur récupérant l'entity manager
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->matchsRepo = $em->getRepository('asudreCDM14Bundle:Matchs');
	}
	
	/**
	 * Récupération de l'objet associé au match
	 * @param unknown $idMatch
	 */
	public function getMatch($idMatch) {
		$match = $this->matchsRepo->getMatch($idMatch)[0];
		return $match;
	}
	
	/**
	 * Récupération des matchs par ordre de date
	 */
	public function recuperationMatchsOrdDate() {
		return $this->matchsRepo->getMatchsOrdDate();
	}
	
	/**
	 * Récupère le dernier match joué
	 * @return Ambigous <multitype:, \Doctrine\ORM\mixed, \Doctrine\ORM\Internal\Hydration\mixed, \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function getDernierMatchJoue() {
		$match = $this->matchsRepo->getDernierMatchJoue();
		
		if($match != null) {
			return $match[0];
		}
		
		return null;
	}

	/**
	 * Ajoute ou modifie le score en base
	 * @param unknown $idMatch
	 * @param unknown $scoreEq1
	 * @param unknown $scoreEq2
	 */
	public function ajoutScore($match, $scoreEq1, $scoreEq2) {
		$idMatch = $match->getId();
		$idEquipe1 = $match->getEquipe1()->getId();
		$idEquipe2 = $match->getEquipe2()->getId();	
		
		return $this->matchsRepo->ajoutScore($idMatch, $idEquipe1, $idEquipe2, $scoreEq1, $scoreEq2);
	}
}