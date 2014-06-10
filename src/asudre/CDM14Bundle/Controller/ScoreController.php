<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use asudre\CDM14Bundle\Entity\Mises;
use asudre\CDM14Bundle\Entity\Matchs;
use asudre\CDM14Bundle\Entity\Equipes;

class ScoreController extends Controller
{
	private $serviceMises;
	private $serviceEquipes;
	private $serviceMatchs;
	
    public function indexAction()
    {

    	// Récupération du service des mises
    	$this->serviceMises = $this->container->get('asudre.serviceMises');
    	
    	// Récupération du service des matchs
    	$this->serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	
    	// Récupération du service des equipes
    	$this->serviceEquipes = $this->container->get('asudre.serviceEquipes');
    	
    	// Récupération de l'utilisateur connecté
    	$usr= $this->get('security.context')->getToken()->getUser();
    	
    	// Récupération de la requête
        $request = $this->getRequest();
    	
        $idMatch = $request->request->get("idMatch");
    	$scoreEq1 = $request->request->get("scoreEquipe1");
    	$scoreEq2 = $request->request->get("scoreEquipe2");
    	
    	$retour = $this->gestionScore($idMatch, $scoreEq1, $scoreEq2, $usr);
    	
		return new Response($retour);
	}
	
	/**
	 * Vérifie la validité des données et modifie la base
	 * @param unknown $idMatch
	 * @param unknown $scoreEq1
	 * @param unknown $scoreEq2
	 */
	private function gestionScore($idMatch, $scoreEq1, $scoreEq2, $utilisateur) {
		$retour;
		$match = null;
		
		if(is_numeric($idMatch) && is_numeric($scoreEq1) && is_numeric($scoreEq2) && is_int($idMatch + 0) && is_int($scoreEq1 + 0) && is_int($scoreEq2 + 0)) {
		
			$match = $this->serviceMatchs->getMatch($idMatch);
			
			if($match->getDate() > new \DateTime("now")) {
				$retour = Matchs::ERREUR_MATCH_NON_COMMENCE;
			}
			else {
				$this->ajoutScore($match, $scoreEq1, $scoreEq2);
				$retour = Mises::OK;
			}
		}
		else {
			$retour = Mises::ERREUR_VALEURS_FORM;
		}
		
		return $this->retourXML($retour, $match, $utilisateur);
		
	}
	
	/**
	 * Ajout du score en base
	 */
	private function ajoutScore($match, $scoreEq1, $scoreEq2) {
		$retour = $this->serviceMatchs->ajoutScore($match, $scoreEq1, $scoreEq2);
	}
	
	/**
	 * Structure les données retounées pour la mise à jour de l'affichage
	 */
	private function retourXML($retour, $match, $utilisateur) {
		$xml = "";
		
		if($retour == Mises::OK) {
			
			$msgInfo = $this->get('translator')->trans('msg.info.score');
			
			$cotes = $this->serviceMises->getCotesMatch($match);
			
			$sommeMises = $this->serviceMises->getSommeMises($utilisateur->getId());
			
			// Récupération de la requête
			$request = $this->getRequest();
			$request->getSession()->set("sommeMises", $sommeMises);
			
			$cagnotte = $utilisateur->getCagnotte() - $sommeMises;

			$xml .=
			"<match>".
				"<message>" . "</message>" .
				"<messageInfo>" .$msgInfo. "</messageInfo>" .
				"<joueur>" .
				"<cagnotte>" .round($cagnotte, 2). "</cagnotte>" .
				"</joueur>" .
			"</match>";
		}
		elseif ($retour == Mises::ERREUR_MATCH_NON_COMMENCE) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.match.non.commence')."</msgErreur>";
		}
		elseif ($retour == Mises::ERREUR_VALEURS_FORM) {
			$xml.=
			"<msgErreur>".$this->get('translator')->trans('erreur.valeurs.form')."</msgErreur>";
		}
		else {
			
		}
		return $xml;
	}
	
}
