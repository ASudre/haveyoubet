<?php

namespace asudre\CreationCDMBundle\Services;

use asudre\CDM14Bundle\Entity\Equipes;
use asudre\CDM14Bundle\Entity\Matchs;
use asudre\CDM14Bundle\Entity\EquipesRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Permet la création des équipes
 * @author Arthur
 *
 */
class ServiceCreationMatchs
{
	
	/**
	 * EquipesRepository permettant d'effectuer les requetes sur la table Equipes
	 */
	private $equipesRepo;
	
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
		$this->equipesRepo = $em->getRepository('asudreCDM14Bundle:Equipes');
		$this->matchsRepo = $em->getRepository('asudreCDM14Bundle:Matchs');
	}
		
	/**
	 * Supprime tous les matchs de la base de données.
	 */
	public function supprimerTous() {
		$this->matchsRepo->supprimerTous();
	}
	
	/**
	 * Récupération des matchs
	 */
	public function recuperationMatchs() {
		return $this->matchsRepo->getMatchs();
	}
	
	/**
	 * Récupération des matchs sous la forme d'un ArrayCollection
	 */
	public function recuperationCollectionMatchs() {
		$return = new ArrayCollection();
		$matchs = $this->recuperationMatchs();
	
		forEach($matchs as $match) {
			$return->add($match);
		}
	
		return $return;
	}
	
	/**
	 * Crée en base de données les matchs
	 */
	public function sauvegardeMatchs($matchs) {
		return $this->matchsRepo->sauvegardeMatchs($matchs);
	}
	
}