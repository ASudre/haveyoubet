<?php

namespace asudre\CDM14Bundle\Services;

class ServiceInvitations 
{
	
	/**
	 * InvitationsRepository permettant d'effectuer les requetes sur la table Matchs
	 */
	private $invitationsRepo;
	
	private $em;
	
	/**
	 * Constructeur récupérant l'entity manager
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
		$this->invitationsRepo = $em->getRepository('asudreCDM14Bundle:Invitations');
	}
	
	/**
	 * Crée une nouvelle invitation
	 * @param unknown $nomGroupe
	 * @param String $courriels
	 * @param unknown $utilisateur
	 */
	public function creationInvitations($groupe, $courriels, $utilisateur) {

		$arrayCourriels = null;
		$codes = array();
		
		$this->em->getConnection()->beginTransaction();
		
		try {
			
			if($courriels != null && $courriels != "") {
				
				$arrayCourriels = split("[,;\n]", $courriels);
				
				foreach ($arrayCourriels as $cle => $courriel) {
			
					$courriel = trim($courriel);
			
					if(filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
				
						do {
							// Génération du code
							$code = md5(uniqid(rand(), true));
						}
						// Vérification de l'unicité du code
						while($this->invitationsRepo->estCodeUnique($code)[0][1] != 0);
				
						$this->invitationsRepo->creationInvitation($groupe, $courriel, $utilisateur, $code);
						
						$codes[$courriel] = $code;
					}
				}	
			}
			
			$this->em->getConnection()->commit();
			
		}
		catch (Exception $e) {
			$this->em->getConnection()->rollback();
			$this->em->close();
			throw $e;
		}
		
		return $codes;
	}

	/**
	 * Récupère les invitations envoyées par un utilisateur
	 * @param unknown $utilisateur
	 */
	public function getInvitations($utilisateur) {
		return $this->invitationsRepo->getInvitations($utilisateur);
	}
	
	/**
	 * Récupère l'objet invitation à partir du code
	 * @param unknown $codeInvitation
	 */
	public function getInvitation($codeInvitation) {
		$invitation = $this->invitationsRepo->getInvitation($codeInvitation);
		
		if(count($invitation) == 1)
			return $invitation[0];
		else
			return null;
	}
	
	/**
	 * Mise à jour de l'invitation une fois que l'utilisateur s'est inscrit sur le site
	 * @param unknown $invitation
	 * @param unknown $utilisateur
	 */
	public function miseAJourInvitation($invitation, $utilisateur) {
		$this->invitationsRepo->miseAJourInvitation($invitation, $utilisateur);
	}
}