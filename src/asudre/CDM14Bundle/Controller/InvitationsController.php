<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use asudre\CDM14Bundle\Entity\Invitations;

/**
 * Affiche et gère l'écran permettant à l'utilisateur d'inviter des amis
 * @author Arthur
 *
 */
class InvitationsController extends Controller
{
	
	private $serviceGroupes;
	private $serviceInvitations;
	private $serviceUtilisateurs;
	private $serviceMails;
	
	/**
	 * Affichage de la liste des invitations et groupe de l'utilisateur
	 */
    public function indexAction()
    {
    	
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "inviteAmis");
    	
    	// Récupération du service des groupes
    	$this->serviceGroupes = $this->container->get('asudre_serviceGroupes');
    	$this->serviceInvitations = $this->container->get('asudre_serviceInvitations');
    	
    	// Récupération de l'utilisateur connecté
    	$user= $this->get('security.context')->getToken()->getUser();
    	
    	$groupes = $this->serviceGroupes->getGroupesDuCreateur($user);
    	$invitations = $this->serviceInvitations->getInvitations($user);

    	return $this->render('asudreCDM14Bundle:Invitations:index.html.twig', array('groupes' => $groupes, 'invitations' => $invitations));
    	
	}
	
	/**
	 * Récupère les joueurs d'un groupe
	 */
	public function joueursGroupeAction() {
		// Récupération du service des utilisateurs et groupe
		$this->serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
		$this->serviceGroupes = $this->container->get('asudre_serviceGroupes');
		
		$request = $this->getRequest();
		$idGroupe = $request->request->get("idGroupe");
		
		$groupe = null;
		$joueurs = null;
		
		// Récupération de l'utilisateur connecté
		$user = $this->get('security.context')->getToken()->getUser();
		
		if($idGroupe !== null && is_numeric($idGroupe) && is_int($idGroupe + 0)) {
		
			// On vérifie que le groupe est bien un groupe de l'utilisateur courant
			if($this->serviceGroupes->estCreateurGroupe($idGroupe, $user)) {
				
				$groupe = $this->serviceGroupes->getGroupe($idGroupe);
				$joueurs = $this->serviceUtilisateurs->recuperationUtilisateursGroupe($idGroupe);
			}
		}
		
		return new Response($this->retourXMLJoueursGroupe($groupe, $joueurs));
	}
	
	/**
	 * Ajout d'un joueur à partir de son pseudonyme
	 */
	public function ajoutJoueurAction() {
		// Récupération des services
		$this->serviceGroupes = $this->container->get('asudre_serviceGroupes');
		$this->serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
		 
		$request = $this->getRequest();
		$idGroupe = $request->request->get("idGroupe");
		$pseudonyme = $request->request->get("pseudonyme");
		
		$retourXMLAjoutJoueur = $this->gestionAjoutJoueur($idGroupe, $pseudonyme);
		
		return new Response($retourXMLAjoutJoueur);
	}
	
	/**
	 * Gestion d'une nouvelle invitation
	 */
	public function invitationAction() {
		// Récupération des services
    	$this->serviceMails = $this->container->get('asudre_serviceMails');
    	$this->serviceGroupes = $this->container->get('asudre_serviceGroupes');
    	$this->serviceInvitations = $this->container->get('asudre_serviceInvitations');
    	
		$request = $this->getRequest();
		$idGroupe = $request->request->get("idGroupe");
		$courriels = $request->request->get("courriels");
		$langue = $request->request->get("langue");

		$retourXMLInvitation = $this->gestionInvitation($idGroupe, $courriels, $langue);
		
		return new Response($retourXMLInvitation);
	}

	/**
	 * Vérifie la validité des données et modifie la base
	 * @param unknown $idGroupe
	 * @param unknown $courriels
	 * @param unknown $langue
	 */
	private function gestionInvitation($idGroupe, $courriels, $langue) {
		$groupe = null;
		
		// français par défaut
		if($langue !== 'en' && $langue !== 'fr') {
			$langue = 'fr';
		}

		// Récupération de l'utilisateur connecté
		$user = $this->get('security.context')->getToken()->getUser();

		if($idGroupe !== null && is_numeric($idGroupe) && is_int($idGroupe + 0)) {
	
			// On vérifie que le groupe est bien un groupe de l'utilisateur courant
			if($this->serviceGroupes->estCreateurGroupe($idGroupe, $user)) {
			
				$groupe = $this->serviceGroupes->getGroupe($idGroupe);
				try {
					$codesInvitations = $this->ajoutInvitations($groupe, $courriels, $langue, $user);
					$retour = Invitations::OK;
				}
				catch (Exception $e) {
					$retour = Invitations::ERREUR_VALEURS_FORM;
				}
			}
			else {
				$retour = Invitations::ERREUR_VALEURS_FORM;
			}
		}
		else {
			$retour = Invitations::ERREUR_VALEURS_FORM;
		}
		
		if($retour == Invitations::OK && $codesInvitations != null) {
			$url = $this->generateUrl('asudre_lienMailInscription', array(), true);
			// envoi du mail d'invitations
			$this->serviceMails->envoiMailsInvitations($langue, $codesInvitations, $url, $user->getUsername());
		}
	
 		return $this->retourXMLInvitation($groupe, $langue, $codesInvitations, $retour);
	
	}
	
	/**
	 * Ajout de l'invitation en base. Retourne le code d'invitation
	 * @param unknown $groupe
	 * @param unknown $courriels
	 * @param unknown $utilisateur
	 */
	private function ajoutInvitations($groupe, $courriels, $langue, $utilisateur) {
		return $this->serviceInvitations->creationInvitations($groupe, $courriels, $langue, $utilisateur);
	}
	
	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 * @param unknown $groupe
	 * @param unknown $langue
	 * @param unknown $codesInvitations
	 * @param unknown $retour
	 */
	private function retourXMLInvitation($groupe, $langue, $codesInvitations, $retour) {
		$xml = "";
	
		if($retour == Invitations::OK) {
			
			$courriels = "";
			
			foreach ($codesInvitations as $courriel => $code) {
				$courriels .=	"<courriel>" .$courriel. "</courriel>";
			}
			
				
			$msgInfo = $this->get('translator')->trans('msg.info.invitation',
					array('%nbInvitations%' => count($codesInvitations))
			);
			
			$nomGroupe = $groupe->getNomGroupe();
				
			$xml .=
			"<invitation>".
				"<date>" .date("d/m/Y H:i"). "</date>" .
				"<courriels>" .$courriels. "</courriels>" .
				"<nomGroupe>" .$nomGroupe. "</nomGroupe>" .
				"<langue>" .$langue. "</langue>" .
				"<message>" . "</message>" .
				"<messageInfo>" .$msgInfo. "</messageInfo>" .
			"</invitation>";

		}
		elseif ($retour == Invitations::ERREUR_VALEURS_FORM) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.valeurs.form')."</msgErreur>";
		}
		else {
				
		}
		return $xml;
	}
	
	/**
	 * Vérifie la validité des données et modifie la base
	 * @param unknown $idGroupe
	 * @param unknown $pseudonyme
	 */
	private function gestionAjoutJoueur($idGroupe, $pseudonyme) {
		$groupe = null;
	
		// Récupération de l'utilisateur connecté
		$user = $this->get('security.context')->getToken()->getUser();

		if($idGroupe != "" && is_numeric($idGroupe) && is_int($idGroupe + 0)) {
			
			if($pseudonyme !== null && $pseudonyme != "") {
				
				// vérifier si le pseudonyme existe
				$joueur = $this->serviceUtilisateurs->getUtilisateur($pseudonyme);
				
				if($joueur !== null) {
					
					// vérifier si le joueur n'est pas déjà dans le groupe
					if(!$this->serviceGroupes->estGroupeUtilisateur($idGroupe, $joueur->getId())) {
						
						// On vérifie que le groupe est bien un groupe de l'utilisateur courant
						if($this->serviceGroupes->estCreateurGroupe($idGroupe, $user)) {
								
							$groupe = $this->serviceGroupes->getGroupe($idGroupe);
							
							try {
								$this->serviceUtilisateurs->ajoutGroupeJoueur($groupe, $pseudonyme);
								$retour = Invitations::OK;
							}
							catch (Exception $e) {
								$retour = Invitations::ERREUR_VALEURS_FORM;
							}
						}
						else {
							$retour = Invitations::ERREUR_VALEURS_FORM;
						}
					}
					else {
						$retour = Invitations::ERREUR_DEJA_DANS_GROUPE;
					}
				}
				else {
					$retour = Invitations::ERREUR_PSEUDONYME_INVALIDE;
				}
			}
			else {
				$retour = Invitations::ERREUR_PSEUDONYME_INVALIDE;
			}
		}
		else {
			$retour = Invitations::ERREUR_VALEURS_FORM;
		}
	
		return $this->retourXMLAjoutJoueur($groupe, $pseudonyme, $retour);
	
	}
		
	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 * @param unknown $groupe
	 * @param unknown $pseudonyme
	 * @param unknown $retour
	 */
	private function retourXMLAjoutJoueur($groupe, $pseudonyme, $retour) {
		$xml = "";
	
		if($retour == Invitations::OK) {
			$nomGroupe = $groupe->getNomGroupe();
			
			$msgInfo = $this->get('translator')->trans('msg.info.ajoutJoueur',
					array('%pseudonyme%' => $pseudonyme,
						  '%groupe%' => $nomGroupe)
			);
	
			$xml .=
			"<invitation>".
			"<date>" .date("d/m/Y H:i"). "</date>" .
			"<pseudonyme>" .$pseudonyme. "</pseudonyme>" .
			"<nomGroupe>" .$nomGroupe. "</nomGroupe>" .
			"<message>" . "</message>" .
			"<messageInfo>" .$msgInfo. "</messageInfo>" .
			"</invitation>";
	
		}
		elseif ($retour == Invitations::ERREUR_VALEURS_FORM) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.valeurs.form')."</msgErreur>";
		}
		elseif ($retour == Invitations::ERREUR_PSEUDONYME_INVALIDE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.pseudonyme.invalide')."</msgErreur>";
		}
		elseif ($retour == Invitations::ERREUR_DEJA_DANS_GROUPE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.deja.dans.groupe')."</msgErreur>";
		}
		else {
	
		}
		return $xml;
	}
	

	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 * @param unknown $groupes
	 */
	private function retourXMLJoueursGroupe($groupe, $arrayJoueurs) {
		$xml = "";

		if($groupe !== null && $arrayJoueurs !== null) {
			$joueurs = "";
			$nomGroupe = $groupe->getNomGroupe();
			
			$msgInfo = $this->get('translator')->trans('msg.info.joueursGroupe',
					array('%groupe%' => $nomGroupe)
			);
			
			foreach ($arrayJoueurs as $index => $joueur) {
				$joueurs .=	"<joueur>" .$joueur. "</joueur>";
			}
			
	
			$xml .=
			"<invitation>".
				"<date>" .date("d/m/Y H:i"). "</date>" .
				"<joueurs>" .$joueurs. "</joueurs>" .
				"<message>" . "</message>" .
				"<groupe>" . $nomGroupe . "</groupe>" .
				"<messageInfo>" . $msgInfo . "</messageInfo>" .
			"</invitation>";
	
		}
		else {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.valeurs.form')."</msgErreur>";
		}

		return $xml;
	}
}
