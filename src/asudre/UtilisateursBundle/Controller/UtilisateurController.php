<?php

namespace asudre\UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UtilisateurController extends Controller
{
	private $serviceUtilisateurs;
	private $serviceInvitations;
	private $premierChargement;

	/**
	 * Récupération des informations à partir du code d'inscription
	 */
    public function lienMailInscriptionAction()
    {
    	$request = $this->getRequest();
    	$codeInscription = $request->query->get('codeInscription');
    	
    	$langue = $request->query->get('lang');
    	$request->setLocale($langue);
    	$this->get('session')->set('_locale', $langue);
    	
    	
    	$this->serviceInvitations = $this->container->get('asudre_serviceInvitations'); 
    	$invitation = $this->serviceInvitations->getInvitation($codeInscription);

    	$email = null;
    	
    	if($invitation != null) {
    		$email = $invitation->getCourriel();
    	}
    	
    	return $this->render('asudreUtilisateursBundle:gestionUtilisateur:inscription.html.twig', array('username' => null, 'email' => $email, 'codeInscription' => $codeInscription, 'msgErreur' => null));
  	}

	/**
	 * Gestion de l'inscription
	 */
	public function inscriptionAction()
	{

		$request = $this->getRequest();

		$username = $request->request->get("username");
		$email = $request->request->get("courriel");

		$this->premierChargement = false;
		$codeInscription = $request->request->get("codeInscription");
		
		if($codeInscription == null) {
			$this->premierChargement = true;
			$codeInscription = $request->query->get("codeInscription");
		}
		
		$motDePasse = $request->request->get("password");
		$motDePasse2 = $request->request->get("password2");
		
		// Récupération du service des utilisateurs
		$this->serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
		$this->serviceInvitations = $this->container->get('asudre_serviceInvitations');
		
		$invitation = $this->serviceInvitations->getInvitation($codeInscription);

		$msg = $this->verificationChamps($username, $email, $invitation, $motDePasse, $motDePasse2);

		if($msg === null) {
			$utilisateur = $this->serviceUtilisateurs->creationUtilisateur($username, $email, $codeInscription, $motDePasse, $invitation->getGroupe(), $invitation->getLangue());
			$this->serviceInvitations->miseAJourInvitation($invitation, $utilisateur);
		}
		else {
			return $this->render('asudreUtilisateursBundle:gestionUtilisateur:inscription.html.twig', array('username' => $username, 'email' => $email, 'codeInscription' => $codeInscription, 'msgErreur' => $msg));
		}
		
		return $this->redirect( $this->generateUrl('fos_user_security_login') );
	}
	
	private function verificationChamps($username, $email, $invitation, $motDePasse, $motDePasse2) {
		$msg = null;
		
		if($invitation == null) {
			// Invitation non valide
			$msg = $this->get('translator')->trans('inscription.invitation.non.valide');
		}
		elseif($invitation->getInvite() != null) {
			// invitation déjà utilisée
			$msg = $this->get('translator')->trans('inscription.invitation.utilisee');
		}
		elseif($this->premierChargement == false) {
			
			if($invitation->getGroupe() == null) {
				$msg = $this->get('translator')->trans('inscription.invitation.non.valide');
			}
			elseif(strlen($motDePasse) < 8) {
				// Mot de passe trop court
				$msg = $this->get('translator')->trans('inscription.motDePasse.trop.court');
			}
			elseif($motDePasse != $motDePasse2) {
				// Mots de passe différents
				$msg = $this->get('translator')->trans('inscription.motsDePasse.non.identiques');
			}
			elseif($username == "") {
				// nom d'utilisateur non valide
				$msg = $this->get('translator')->trans('inscription.nomUtilisateur.non.valide');
			}
			elseif ($this->serviceUtilisateurs->estUsernameUtilise($username)) {
				// nom d'utilisateur déjà utilisé
				$msg = $this->get('translator')->trans('inscription.nomUtilisateur.utilise');
			}
			elseif ($this->serviceUtilisateurs->estCourrielUtilise($email)) {
				// courriel déjà utilisé
				$msg = $this->get('translator')->trans('inscription.courriel.utilise');
			}
			
		}
		else {
			// On n'affiche les autres erreurs si l'utilisateur arrive juste sur la page
			$msg = "";
		}
		
		return $msg;
	}
}
