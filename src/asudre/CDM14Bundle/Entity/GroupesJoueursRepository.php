<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\EntityRepository;
use asudre\CDM14Bundle\Entity\GroupesJoueurs;

/**
 * groupesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupesJoueursRepository extends EntityRepository
{
	
	/**
	 * Vérifie si le nom du groupe est utilisé
	 * @param unknown $nomGroupe
	 */
	public function estNomUtilise($nomGroupe) {
		$query = $this->_em->createQuery('SELECT count(a) FROM asudreCDM14Bundle:GroupesJoueurs a WHERE a.nomGroupe = :nom');
		$query->setParameters(array(
				'nom' => $nomGroupe
		));
		
		return $query->getScalarResult();
	}
	
	/**
	 * Création d'un groupe
	 * @param unknown $nomGroupe
	 * @param unknown $utilisateur
	 */
	public function creationGroupe($nomGroupe, $utilisateur) {
		$groupe = new GroupesJoueurs();
		$groupe->setCreateur($utilisateur);
		$groupe->setNomGroupe($nomGroupe);
		$groupe->addMembre($utilisateur);
		
		$this->_em->persist($groupe);
		
		// Étape 2 : On « flush » tout ce qui a été persisté avant
		$this->_em->flush();

		$utilisateur->addGroupesJoueur($groupe);

		$this->_em->persist($utilisateur);
		
		$this->_em->flush();
		
		return $groupe->getId();
	}
	

	/**
	 * Récupération du groupe
	 * @param unknown $idGroupe
	 */
	public function getGroupe($idGroupe) {
		$query = $this->_em->createQuery('SELECT a FROM asudreCDM14Bundle:GroupesJoueurs a WHERE a.id = :groupe');
		$query->setParameters(array(
				'groupe' => $idGroupe
		));
	
		return $query->getResult();
	}
	
	/**
	 * Récupère les groupes du créateur
	 * @param unknown $utilisateur
	 */
	public function getGroupesDuCreateur($utilisateur){
		$query = $this->_em->createQuery('SELECT a FROM asudreCDM14Bundle:GroupesJoueurs a WHERE a.createur = :utilisateur order by a.date desc');
		$query->setParameters(array(
				'utilisateur' => $utilisateur
		));
		
		return $query->getResult();
	}
	
	/**
	 * Vérifie si l'utilisateur est créateur du groupe
	 * @param unknown $idGroupe
	 * @param unknown $utilisateur
	 */
	public function estCreateurGroupe($idGroupe, $utilisateur) {
		$query = $this->_em->createQuery('SELECT count(a) FROM asudreCDM14Bundle:GroupesJoueurs a WHERE a.id = :id AND a.createur = :utilisateur');
		$query->setParameters(array(
				'id' => $idGroupe,
				'utilisateur' => $utilisateur
		));
		
		return $query->getScalarResult();
	}
	
	/**
	 * Vérifie si l'utilisateur est membre du groupe
	 * @param unknown $idGroupe
	 * @param unknown $utilisateur
	 */
	public function estGroupeUtilisateur($idGroupe, $utilisateur) {
		$query = $this->_em->createQuery('SELECT count(ut) FROM asudreUtilisateursBundle:Utilisateur ut
										JOIN ut.groupesJoueurs g
										WHERE g.id = :idGroupe AND ut.id = :utilisateur');
		$query->setParameters(array(
				'idGroupe' => $idGroupe,
				'utilisateur' => $utilisateur
		));
	
		return $query->getScalarResult();
	}
	
	/**
	 * Récupère les utilisateurs d'un groupe
	 * @param unknown $idUtilisateur
	 */
	public function getGroupesUtilisateur($idUtilisateur) {
		$query = $this->_em->createQuery('SELECT a FROM asudreUtilisateursBundle:Utilisateur a WHERE a.id = :utilisateur');
		$query->setParameters(array(
				'utilisateur' => $idUtilisateur
		));
		
		return $query->getResult();
	}
	
	public function miseAJourGroupe($groupe) {
		$this->_em->persist($groupe);
		$this->_em->flush();
	}
	
}