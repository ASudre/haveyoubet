<?php

namespace asudre\CDM14Bundle\Services;

use asudre\CDM14Bundle\Entity\Mises;

class ServiceMises 
{
	
	/**
	 * MisesRepository permettant d'effectuer les requetes sur la table Mises
	 */
	private $misesRepo;
	
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
		$this->misesRepo = $em->getRepository('asudreCDM14Bundle:Mises');
		$this->matchsRepo = $em->getRepository('asudreCDM14Bundle:Matchs');
	}
	
	/**
	 * Ajoute une mise pour un match et un utilisateur
	 * @param unknown $utilisateur
	 * @param unknown $equipe
	 * @param unknown $match
	 * @param unknown $valeur
	 */
	public function ajoutMise($utilisateur, $equipe, $match, $valeur) {
		return $this->misesRepo->ajoutMise($utilisateur, $equipe, $match, $valeur);
	}
	
	/**
	 * Récupère la cote des matchs
	 * @param unknown $match
	 * @return multitype:Ambigous <number, string>
	 */
	public function getCotesMatch($match) {
		$mises = $this->misesRepo->getMisesMatch($match->getId());
		$sommeMisesNul = 0;
		$sommeMisesEq1 = 0;
		$sommeMisesEq2 = 0;
		
		foreach ($mises as $mise)
		{
			if($mise->getEquipe()->getId() == $match->getEquipe1()->getId()) {
				$sommeMisesEq1 += $mise->getValeur();
			}
			elseif ($mise->getEquipe()->getId() == $match->getEquipe2()->getId()) {
				$sommeMisesEq2 += $mise->getValeur();
			}
			// Mise sur un match nul
			else {
				$sommeMisesNul += $mise->getValeur();
			}
		}
		
		$coteNul = $sommeMisesNul != 0 ? 1 + ($sommeMisesEq1 + $sommeMisesEq2) / $sommeMisesNul : 0;
		$coteEq1 = $sommeMisesEq1 != 0 ? 1 + ($sommeMisesEq2 + $sommeMisesNul) / $sommeMisesEq1 : 0;
		$coteEq2 = $sommeMisesEq2 != 0 ? 1 + ($sommeMisesNul + $sommeMisesEq1) / $sommeMisesEq2 : 0;
		
		return array(
		    "nul" => $coteNul,
		    "eq1" => $coteEq1,
		    "eq2" => $coteEq2
		);
		
	}
	
	/**
	 * Récupère l'ensemble des mises d'un utilisateur et crée une map avec les idMatch
	 * @param unknown $idUtilisateur
	 */
	public function recuperationMises($idUtilisateur) {
		$mises = $this->misesRepo->getMisesUtilisateur($idUtilisateur);
		
		$matchMises = null;
		
		for($i = 0; $i<count($mises); $i++)
		{
			$idMatch = $mises[$i]->getMatch()->getId();
			$matchMises[$idMatch][] = $mises[$i];
		}
		
		return $matchMises;
	}
	
	/**
	 * Récupération de la somme des mises d'un joueur pour le calcul de sa cagnotte courante
	 * @param unknown $idUtilisateur
	 */
	public function getSommeMises($idUtilisateur) {
		return $this->misesRepo->getSommeMises($idUtilisateur)[0]['total'];
	}
}