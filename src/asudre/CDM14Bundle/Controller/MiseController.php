<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use asudre\CDM14Bundle\Entity\Mises;
use asudre\CDM14Bundle\Entity\Matchs;
use asudre\CDM14Bundle\Entity\Equipes;

class MiseController extends Controller
{
	private $serviceMises;
	private $serviceEquipes;
	private $serviceUtilisateurs;
	private $serviceMatchs;
	
    public function indexAction()
    {

    	// Récupération du service des mises
    	$this->serviceMises = $this->container->get('asudre.serviceMises');

    	// Récupération du service des utilisateurs
    	$this->serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
    	
    	// Récupération du service des matchs
    	$this->serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	
    	// Récupération du service des equipes
    	$this->serviceEquipes = $this->container->get('asudre.serviceEquipes');
    	
    	// Récupération de l'utilisateur connecté
    	$usr= $this->get('security.context')->getToken()->getUser();
    	
    	// Récupération de la requête
        $request = $this->getRequest();
    	
        $idMatch = $request->request->get("match");
    	$mise = $request->request->get("mise");
    	$idEquipe = $request->request->get("select");
    	
    	$sommeMises = $request->getSession()->get("sommeMises");
    	
    	$retour = $this->gestionMise($usr, $idMatch, $idEquipe, $mise, $sommeMises);
    	
		return new Response($retour);
	}
	
	/**
	 * Récupère l'objet équipe à partir de son id et de l'objet match
	 * @param unknown $match
	 * @param unknown $idEquipe
	 */
	private function getEquipe($match, $idEquipe) {
		$equipe = null;
		
		if ($idEquipe == Equipes::ID_NUL) {
			$equipe = $this->serviceEquipes->getEquipeNul();
		}
		elseif($idEquipe == $match->getEquipe1()->getId()) {
			$equipe = $match->getEquipe1();
		}
		elseif ($idEquipe == $match->getEquipe2()->getId()) {
			$equipe = $match->getEquipe2();
		}
		
		return $equipe;
	}
	
	/**
	 * Vérifie si la mise est valide, ajoute la mise en base et récupère les cotes du match après mise
	 * @param unknown $utilisateur
	 * @param unknown $match
	 * @param unknown $idEquipe
	 * @param unknown $idMatch
	 * @param unknown $mise
	 */
	private function gestionMise($utilisateur, $idMatch, $idEquipe, $mise, $sommeMisesCourantes) {
		$retour;
		$nomEquipe = "";
		$match = null;
		
		if(is_numeric($idMatch) && is_numeric($idEquipe) && is_int($idMatch + 0) && is_int($idEquipe + 0) && is_numeric($mise) && ($mise + 0) >= 0) {
		
			$match = $this->serviceMatchs->getMatch($idMatch);
			
			$cagnotteCourante = $utilisateur->getCagnotte() - $sommeMisesCourantes;
			
			if($match->getDate() <= new \DateTime("now")) {
				$retour = Mises::ERREUR_MATCH_COMMENCE;
			}
			else {
				$equipe = $this->getEquipe($match, $idEquipe);
				
				// Test si l'équipe est bien une équipe du match
				if($equipe != null) {
					$nomEquipe = $equipe->getNom();
					// Test si l'utilisateur a les moyens de miser
					if($cagnotteCourante >= $mise) {
						$this->serviceMises->ajoutMise($utilisateur, $equipe, $match, $mise);
						$retour = Mises::OK;
					}
					else {
						$retour = Mises::ERREUR_CAGNOTTE_TROP_FAIBLE;
					}
				}
				else {
					$retour = Mises::ERREUR_CORRESPONDANCE_MATCH_EQUIPE;
				}
			}
		}
		else {
			$retour = Mises::ERREUR_VALEURS_FORM;
		}
		
		return $this->retourXML($retour, $match, $utilisateur, $nomEquipe, $mise);
		
	}

	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 */
	private function retourXML($retour, $match, $utilisateur, $nomEquipe, $valeur) {
		$xml = "";
		
		if($retour == Mises::OK) {
			
			$msgInfo = $this->get('translator')->trans(
    			'msg.info.mise',
    			array('%equipe%' => $nomEquipe,
					  '%valeur%' => $valeur)
			);
			
			$cotes = $this->getCotes($match);
			
			$sommeMises = $this->serviceMises->getSommeMises($utilisateur->getId());
			
			// Récupération de la requête
			$request = $this->getRequest();
			$request->getSession()->set("sommeMises", $sommeMises);
			
			$cagnotte = $utilisateur->getCagnotte() - $sommeMises;

			$xml .=
			"<match>".
				"<cotes>" .
					"<equipe1>" .round($cotes['eq1'], 2). "</equipe1>" .
					"<equipe2>" .round($cotes['eq2'], 2). "</equipe2>" .
					"<nul>" .round($cotes['nul'], 2). "</nul>" .
				"</cotes>".
				"<mise>" .
					"<equipe>" .$nomEquipe. "</equipe>" .
					"<valeur>" .$valeur. "</valeur>" .
					"<date>" .date("d/m/Y H:i"). "</date>" .
				"</mise>".
				//message qui indique la mise et le gain
				"<message>" . "</message>" .
				"<messageInfo>" .$msgInfo. "</messageInfo>" .
				"<joueur>" .
				"<cagnotte>" .round($cagnotte, 2). "</cagnotte>" .
				"</joueur>" .
			"</match>";
		}
		elseif ($retour == Mises::ERREUR_CAGNOTTE_TROP_FAIBLE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.cagnotte.trop.faible')."</msgErreur>";
		}
		elseif ($retour == Mises::ERREUR_CORRESPONDANCE_MATCH_EQUIPE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.match.concordance')."</msgErreur>";
		}
		elseif ($retour == Mises::ERREUR_MATCH_COMMENCE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.match.commence')."</msgErreur>";
		}
		elseif ($retour == Mises::ERREUR_VALEURS_FORM) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.valeurs.form')."</msgErreur>";
		}
		else {
			
		}
		return $xml;
	}
	
	/**
	 * Calcul cote
	 */
	private function getCotes($match) {
		return $this->serviceMises->getCotesMatch($match);
	}
}
