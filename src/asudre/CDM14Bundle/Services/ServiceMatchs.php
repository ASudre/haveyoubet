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

// 	/**
// 	 * Récupération des matchs par ordre de date et des mises associées de l'utilisateur
// 	 */
// 	public function recuperationMatchsMisesOrdDate($idUtilisateur) {
// 		// Retourne un tableau contenant une case match suivi des cases contenant toutes les mises pour le joueur en question
// 		$matchsMises = $this->matchsRepo->getMatchsMisesOrdDate($idUtilisateur);
		
// 		$tabAffichage;
// 		$j = 0;
		
// 		// Restructuration du tableau pour faciliter l'affichage
// 		for($i = 0; $i < count($matchsMises); $i++) {
			
// 			$k = 0;
			
// 			if($matchsMises[$i] != null) {
// 				if ($matchsMises[$i] instanceof Matchs) {
// 					$tabAffichage[$j][0] = $matchsMises[$i];
// 				}
// 				while ($i+1 < count($matchsMises) && $matchsMises[$i+1] != null && $matchsMises[$i+1] instanceof Mises) {
// 					$tabAffichage[$j][0][$k++] = $matchsMises[++$i];
// 				}
				
// 				$j++;
// 			}
// 		}
		
// 		return $tabAffichage;

// 	}

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