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
class ServiceCreationEquipes
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
	 * Sauvegarde les équipes en base de données
	 * @param unknown $tabEquipes Equipes sous la forme d'un tableau des groupes. Ex : A[Brésil], A[France], ...
	 * @return number
	 */
	public function creationEquipes($tabEquipes) {
		return $this->equipesRepo->sauvegardeEquipesParGroupe($tabEquipes);
	}
	
	/**
	 * Récupère le nombre d'équipes et groupes précédemment enregistrés en base pour la page des groupes
	 */
	public function getNombreEquipesEtGroupes() {
		return $this->equipesRepo->getNombreEquipesEtGroupes();
	}
	
	/**
	 * Récupération des équipes sous la forme d'un tableau des groupes. Ex : A[instance:Brésil], A[instance:France], ...
	 */
	public function recuperationEquipesParGroupe() {
		$tabEquipes;
		
		$equipes = $this->equipesRepo->getEquipes();
		
		if($equipes !== null)
		{
			foreach ($equipes as $equipe) {
				$tabEquipes[$equipe->getGroupe()][] = $equipe;
			}
		}
		
		return $tabEquipes;
	}
	
	/**
	 * Récupération des équipes sous la forme d'un tableau des groupes. Ex : A[instance:Brésil], A[instance:France], ...
	 */
	public function getEquipesParGroupe($equipes) {
		$tabEquipes;
	
		if($equipes !== null)
		{
			foreach ($equipes as $equipe) {
				$tabEquipes[$equipe->getGroupe()][] = $equipe;
			}
		}
	
		return $tabEquipes;
	}
	
	/**
	 * Récupération des équipes ordonnées par groupes. Le tableau retourne est un ArrayCollection
	 */
	public function recuperationEquipesOrdonneesParGroupes() {
		$return = new ArrayCollection();
		$equipes = $this->equipesRepo->getEquipesOrdGroupes();
		
		forEach($equipes as $equipe) {
			$return->add($equipe);
		}
		
		return $return;
	}
	
	/**
	 * Récupération des équipes
	 */
	public function recuperationEquipes() {
		return $this->equipesRepo->getEquipes();
	}
	
	/**
	 * Supprime toutes les équipes de la base de données.
	 */
	public function supprimerToutes() {
		$this->equipesRepo->supprimerToutes();
	}
	
	/**
	 * Crée en base de données les équipes
	 */
	public function sauvegardeEquipes($equipes) {
		return $this->equipesRepo->sauvegardeEquipes($equipes);
	}
	
	/**
	 * Crée en base de données les équipes
	 */
	public function sauvegardeEquipesMatchs($equipes) {
		$retour = 0;
		
		// Sauvegarde des équipes
		$retour = $this->equipesRepo->sauvegardeEquipes($equipes);

		if($retour == 1 && !$this->verifieNbEquipesNbMatchs(count($equipes))) {
			// Sauvegarde des matchs
			$matchsParGroupe = $this->creationMatchs($equipes);
			
			$matchs = array();
			
			foreach ($matchsParGroupe as $matchsGroupe) {
				foreach ($matchsGroupe as $match) {
					$match->setDate(new \Datetime());
					$matchs[] = $match;
				}
			}
			
			return $this->matchsRepo->sauvegardeMatchs($matchs);
		}
		
		return $retour;
	}

	/**
	 * Crée les équipes à partir du nombre de groupes et du nombre d'équipes par groupe
	 */
	public function creationEquipesAVide($nbEquipesParGroupe, $nbGroupes) {
		$this->equipesRepo->creationEquipesAVide($nbEquipesParGroupe, $nbGroupes);
	}
	
	/**
	 * Crée un tableau contenant les matchs à partir des équipes et de leur groupe associé
	 * @param unknown $equipes
	 */
	private function creationMatchs($equipes) {
		$matchs = array();
		 
		$tabEquipesParGroupe = $this->getEquipesParGroupe($equipes);
	
		// On boucle sur les groupes
		foreach ($tabEquipesParGroupe as $gr => $eq) {
			$this->creationMatchsGroupe($matchs, $gr, $eq);
		}
		 
		return $matchs;
	}
	
	/**
	 * Crée les matchs d'un groupe donné
	 * @param unknown $groupe groupe dont les matchs sont à créer
	 * @param unknown $equipes équipes du groupe
	 */
	private function creationMatchsGroupe(&$matchs, $groupe, $equipes) {
	
		if($equipes !== null && count($equipes) != 1) {
	
			// on récupère la première équipe du groupe puis on l'enlève du tableau
			$equipe1 = $equipes[0];
			unset($equipes[0]);
			// On réordonne le tableau des équipes
			$equipes = array_values($equipes);
	
			foreach ($equipes as $id => $equipe2) {
				$match = new Matchs();
		   
				$match->setGroupe($groupe);
				$match->setEquipe1($equipe1);
				$match->setEquipe2($equipe2);
	
				$matchs[$groupe][] = $match;
	
			}
				
			$this->creationMatchsGroupe($matchs, $groupe, $equipes);
		}
		 
	}
	
	/**
	 * Vérifie que les nombres de matchs et d'équipes stockés en base sont égaux à ceux du formulaire
	 * @param unknown $nbEquipes
	 * @return boolean
	 */
	private function verifieNbEquipesNbMatchs($nbEquipes) {
		$nbr = $this->equipesRepo->getNombreEquipesEtGroupes();
		$nbMatchs = $this->matchsRepo->getNombreMatchs();
		$nbEq = $nbr[0][1];
		$nbGr = $nbr[0][2];
		$nbMa = $this->getNbMatchs($nbEq, $nbGr);
		
		return ($nbEq == $nbEquipes && $nbMa == $nbMatchs[0][1]);
	}
	
	private function getNbMatchs($nbEquipes, $nbGroupes) {
		if(is_numeric($nbEquipes) && is_numeric($nbGroupes) && is_int($nbEquipes/1) && is_int($nbGroupes/1) && is_int($nbEquipes / $nbGroupes)) {
			$nbEqParGr = $nbEquipes / $nbGroupes;
			$nbMatchsParGroupe = 0;
			for($i=$nbEqParGr-1 ; $i!=0 ; $i--) {
				$nbMatchsParGroupe += $i;
			}
			return $nbGroupes * $nbMatchsParGroupe;
		}
		else {
			throw new \InvalidArgumentException('Les valeurs du nombre d\'équipes et du nombre de groupes ne sont pas entières ou leur quotient n\'est pas entier');
		}
	}
}