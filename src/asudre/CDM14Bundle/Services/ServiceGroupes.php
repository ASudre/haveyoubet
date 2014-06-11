<?php

namespace asudre\CDM14Bundle\Services;

class ServiceGroupes 
{
	
	/**
	 * GroupesRepository permettant d'effectuer les requetes sur la table Matchs
	 */
	private $groupesRepo;
	
	/**
	 * Constructeur récupérant l'entity manager
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->groupesRepo = $em->getRepository('asudreCDM14Bundle:GroupesJoueurs');
	}
	
	/**
	 * Crée un nouveau groupe
	 * @param unknown $nomGroupe
	 * @param unknown $utilisateur
	 */
	public function creationGroupe($nomGroupe, $utilisateur) {
		return $this->groupesRepo->creationGroupe($nomGroupe, $utilisateur);
	}
	
	/**
	 * Vérifie si le nom du groupe est déjà utilisé
	 * @param unknown $nomGroupe
	 */
	public function estNomUtilise($nomGroupe) {
 		return $this->groupesRepo->estNomUtilise($nomGroupe)[0][1] != 0;
	}
	
	/**
	 * Récupère les groupes créés par un utilisateur
	 * @param unknown $utilisateur
	 */
	public function getGroupesDuCreateur($utilisateur) {
		return $this->groupesRepo->getGroupesDuCreateur($utilisateur);
	}
	
	/**
	 * Récupération du groupe
	 * @param unknown $idGroupe
	 */
	public function getGroupe($idGroupe) {
		return $this->groupesRepo->getGroupe($idGroupe)[0];
	}

	/**
	 * Vérifie qu'un utilisateur est bien créateur du groupe
	 * @param unknown $idGroupe
	 * @param unknown $utilisateur
	 */
	public function estCreateurGroupe($idGroupe, $utilisateur) {
		return $this->groupesRepo->estCreateurGroupe($idGroupe, $utilisateur)[0][1] != 0;
	}

	/**
	 * Vérifie qu'un utilisateur est bien membre du groupe
	 * @param unknown $idGroupe
	 * @param unknown $idUtilisateur
	 */
	public function estGroupeUtilisateur($idGroupe, $idUtilisateur) {
		return $this->groupesRepo->estGroupeUtilisateur($idGroupe, $idUtilisateur)[0][1] != 0;
	}
	
	/**
	 * Récupère les groupes d'un utilisateur
	 * @param unknown $idUtilisateur
	 * @return boolean
	 */
	public function getGroupesUtilisateur($idUtilisateur) {
		return $this->groupesRepo->getGroupesUtilisateur($idUtilisateur);
	}
	
}