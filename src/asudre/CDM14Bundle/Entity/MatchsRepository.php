<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MatchsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MatchsRepository extends EntityRepository
{

	/**
	 * Sauvegarde des matchs à partir des matchs par défaut
	 * @param unknown $matchs
	 */
	function sauvegardeMatchs($matchs) {
	
		foreach ($matchs as $match)
		{
			// Étape 1 : On « persiste » l'entité
			$this->_em->persist($match);
		}
	
		// Étape 2 : On « flush » tout ce qui a été persisté avant
		$this->_em->flush();
	
		return 1;
	}
	
	/**
	 * Vérifie qu'une équipe est bien associé au match lorsqu'un utilisateur mise
	 * @param unknown $idMatch
	 * @param unknown $idEquipe
	 */
	public function verificationMatchEquipe($idMatch, $idEquipe) {
		$query = $this->_em->createQuery('SELECT count(a) FROM asudreCDM14Bundle:Matchs a WHERE a.id = :match AND (a.equipe1 = :eq1 OR a.equipe2 = :eq2)');
		$query->setParameters(array(
				'match' => $idMatch,
				'eq1' => $idEquipe,
				'eq2' => $idEquipe,
		));
		
		return $query->getScalarResult();
	}
	
	/**
	 * Supprime tous les matchs
	 */
	function supprimerTous() {
		// Supprime toutes les valeurs de la table Matchs
		$query = $this->_em->createQuery('DELETE FROM asudreCDM14Bundle:Matchs a');
		return $query->execute();
	}
	
	/**
	 * Récupération du match
	 * @param unknown $idMatch
	 */
	function getMatch($idMatch) {
		$query = $this->_em->createQuery('SELECT a FROM asudreCDM14Bundle:Matchs a WHERE a.id = :match');
		$query->setParameters(array(
				'match' => $idMatch
		));
		
		return $query->getResult();
	}
	
	/**
	 * Récupère l'ensemble des matchs de la table
	 */
	function getMatchs() {
		return $equipes = $this->findAll();
	}
	
	/**
	 * Récupération du nombre de matchs sauvegardés en base
	 */
	function getNombreMatchs() {
		$query = $this->_em->createQuery('SELECT count(a) FROM asudreCDM14Bundle:Matchs a');
		return $query->getScalarResult();
	}
	
	/**
	 * Récupération des matchs ordonnés par date
	 */
	function getMatchsOrdDate() {
		$query = $this->createQueryBuilder('a');
		$query->orderBy('a.date', 'ASC');
		return $query->getQuery()->getResult();
	}
	
	/**
	 * Récupération des matchs ordonnés par date et des mises associées
	 * @param unknown $idUtilisateur Identifiant de l'utilisateur connecté dont on récupère les mises
	 */
	function getMatchsMisesOrdDate($idUtilisateur) {
		$query = $this->_em->createQuery('SELECT ma, mi FROM asudreCDM14Bundle:Matchs ma
				left join asudreCDM14Bundle:Mises mi WITH ma.id = mi.match and mi.utilisateur = '.$idUtilisateur.' order by ma.date');
		return $query->getResult();
	}
	
	/**
	 * Gestion des scores en base en appelant la procédure pl/sql
	 * @param unknown $idVainqueur
	 * @param unknown $idEquipe1
	 * @param unknown $idEquipe2
	 * @param unknown $scoreEq1
	 * @param unknown $scoreEq2
	 */
	public function ajoutScore($idMatch, $idEquipe1, $idEquipe2, $scoreEq1, $scoreEq2) {
		$sth = $this->_em->getConnection()->prepare("CALL majCagnottes(".$idEquipe1.", ".$idEquipe2.", ".$idMatch.", ".$scoreEq1.", ".$scoreEq2.")");
		$sth->execute();
	}
	
}