<?php

namespace asudre\CDM14Bundle\Services;

class ServiceUtilisateurs 
{
	
	/**
	 * UtilisateursRepository permettant d'effectuer les requetes sur la table Matchs
	 */
	private $utilisateursRepo;
	
	private $groupesRepo;
	
	private $em;
	
	private $userManager;

	/**
	 * Constructeur récupérant l'entity manager
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, \FOS\UserBundle\Model\UserManager $userManager)
	{
		$this->em = $em;
		$this->utilisateursRepo = $em->getRepository('asudreUtilisateursBundle:Utilisateur');
		$this->groupesRepo = $em->getRepository('asudreCDM14Bundle:GroupesJoueurs');
		$this->userManager = $userManager;
	}
	

	/**
	 * Récupère les utilisateurs d'un groupe
	 * @param unknown $idMatch
	 */
	public function recuperationUtilisateursGroupe($idGroupe) {
		return $this->utilisateursRepo->getUtilisateursGroupe($idGroupe);
	}
	
	/**
	 * Récupère les utilisateurs ordonnés par valeur de cagnotte pour le match passé en paramètre
	 * @param unknown $match
	 */
	public function recuperationUtilisateursOrdCagnotte($match) {
		return $this->utilisateursRepo->getUtilisateursOrdCagnotte($match);
	}
	
	/**
	 * Récupère les utilisateurs ordonnés par valeur de cagnotte pour le match passé en paramètre par groupe
	 * @param unknown $match
	 * @param unknown $idGroupe
	 */
	public function getUtilisateursOrdCagnotteParGroupe($match, $idGroupe) {
		return $this->utilisateursRepo->getUtilisateursOrdCagnotteParGroupe($match, $idGroupe);
	}
	
	/**
	 * Vérifie si le nom d'utilisateur est déjà utilisé
	 * @param unknown $username
	 */
	public function estUsernameUtilise($username) {
		if($this->utilisateursRepo->getUtilisateurByUsername($username) == null) {
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * Récupère l'utilisateur du pseudonyme
	 * @param unknown $username
	 */
	public function getUtilisateur($username) {
		return $this->utilisateursRepo->getUtilisateurByUsername($username)[0];
	}
	
	/**
	 * Vérifie si le courriel est déjà utilisé
	 * @param unknown $courriel
	 */
	public function estCourrielUtilise($courriel) {
		if($this->utilisateursRepo->getUtilisateurByCourriel($courriel) == null) {
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * Récupère la cagnotte de l'utilisateur
	 * @param unknown $idUtilisateur
	 */
	public function getCagnotte($idUtilisateur) {
		$utilisateur = $this->utilisateursRepo->getUtilisateur($idUtilisateur);
		if($utilisateur == null) {
			return 0;
		}
		else {
			return $utilisateur->getCagnotte();
		}
	}
	
	/**
	 * Création du nouvel utilisateur et modification de l'invitation pour ajouter l'invité
	 * @param unknown $username
	 * @param unknown $email
	 * @param unknown $codeInvitation
	 * @param unknown $motDePasse
	 * @param unknown $groupeUtilisateur
	 * @param unknown $langue
	 */
	public function creationUtilisateur($username, $email, $codeInvitation, $motDePasse, $groupeUtilisateur, $langue) {
		$utilisateur = $this->userManager->createUser();
		$utilisateur->setUsername($username);
		$utilisateur->setEmail($email);
		$utilisateur->setEnabled(true);
		$utilisateur->setPlainPassword($motDePasse);
		$utilisateur->setLangue($langue);
		$utilisateur->addGroupesJoueur($groupeUtilisateur);
		
		$this->userManager->updateUser($utilisateur);
		
		$groupeUtilisateur->addMembre($utilisateur);
		$this->groupesRepo->miseAJourGroupe($groupeUtilisateur);
		
		return $utilisateur;
		
	}
	
	/**
	 * Ajoute un utilisateur à un groupe
	 * @param unknown $idGroupe
	 * @param unknown $utilisateur
	 */
	public function ajoutGroupeJoueur($groupe, $pseudonyme) {
		$utilisateur = $this->utilisateursRepo->getUtilisateurByUsername($pseudonyme);
		
		if($utilisateur != null && $utilisateur[0] != null) {
			$utilisateur[0]->addGroupesJoueur($groupe);
			$this->userManager->updateUser($utilisateur[0]);
	
			$groupe->addMembre($utilisateur[0]);
			$this->groupesRepo->miseAJourGroupe($groupe);
			
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Récupère les informations pour l'affichage du graphique de classement
	 * @param unknown $match
	 */
	public function getUtilisateursGainsMatchsTous($match) {
		return $this->utilisateursRepo->getUtilisateursGainsMatchsTous($match);
	}
}