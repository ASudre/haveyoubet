<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use asudre\CDM14Bundle\Entity\GroupesJoueurs;

class GroupeController extends Controller
{
	private $serviceGroupes;
	
    public function indexAction()
    {

    	// Récupération du service des groupes
    	$this->serviceGroupes = $this->container->get('asudre_serviceGroupes');

    	// Récupération de l'utilisateur connecté
    	$usr= $this->get('security.context')->getToken()->getUser();
    	
    	// Récupération de la requête
        $request = $this->getRequest();
    	
        $nomGroupe = $request->request->get("nomGroupe");
    	
    	$retour = $this->creationGroupe($nomGroupe, $usr);
    	
		return new Response($retour);
	}
	
	/**
	 * Crée un nouveau groupe en base pour l'utilisateur
	 * @param unknown $nomGroupe
	 * @param unknown $utilisateur
	 */
	private function creationGroupe($nomGroupe, $utilisateur) {
		$retour;
		$idGroupe = null;
		
		if($nomGroupe == null || $nomGroupe == "") {
			$retour = GroupesJoueurs::ERREUR_NOM_NON_VALIDE;
		}
		elseif(!$this->serviceGroupes->estNomUtilise($nomGroupe)) {
			$idGroupe = $this->serviceGroupes->creationGroupe($nomGroupe, $utilisateur);
			
			if($idGroupe == null) {
				$retour = GroupesJoueurs::ERREUR_NOM_NON_VALIDE;
			}
			else {
				$retour = GroupesJoueurs::OK;
			}
		}
		else {
			$retour = GroupesJoueurs::ERREUR_NOM_UTILISE;
		}

		return $this->retourXML($retour, $idGroupe);
		
	}
	
	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 */
	private function retourXML($retour, $idGroupe) {
		$xml = "";
		
		if($retour == GroupesJoueurs::OK) {
			
			$msgInfo = $this->get('translator')->trans('msg.info.groupe');

			$xml .=
			"<groupe>".
				"<date>" .date("d/m/Y H:i"). "</date>" .
				"<idGroupe>" .$idGroupe. "</idGroupe>" .
				"<message>" . "</message>" .
				"<messageInfo>" .$msgInfo. "</messageInfo>" .
			"</groupe>";
		}
		elseif ($retour == GroupesJoueurs::ERREUR_NOM_UTILISE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('groupes.utilisé')."</msgErreur>";
		}
		elseif ($retour == GroupesJoueurs::ERREUR_NOM_NON_VALIDE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('groupes.nom.invalide')."</msgErreur>";
		}
		elseif ($retour == GroupesJoueurs::ERREUR_SAUVEGARDE_BASE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('groupes.erreur.acces.base')."</msgErreur>";
		}
		else {
			
		}
		return $xml;
	}
	
}
