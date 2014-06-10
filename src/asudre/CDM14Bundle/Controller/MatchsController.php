<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MatchsController extends Controller
{
	/**
	 * Affichage de la liste des matchs pour lesquels va pouvoir miser l'utilisateur
	 */
    public function indexAction()
    {
    	
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "matchs");
    	
		// Récupération de l'utilisateur connecté
    	$usr= $this->get('security.context')->getToken()->getUser();
    	
		// L'utilisateur s'est correctement authentifié, on affiche la liste des matchs
		
    	$serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	$serviceMises = $this->container->get('asudre.serviceMises');
    	
    	$matchs = $serviceMatchs->recuperationMatchsOrdDate();
    	
    	$cotes = array();
    	
    	foreach ($matchs as $match) {
    		// Récupération des cotes
    		$cotes[$match->getId()] = $serviceMises->getCotesMatch($match);
    		
    		// Affichage bouton valider grisé si le match commencé
    		if($match->getDate() <= new \DateTime("now")) {
    			$match->setEstMatchJoue(true);
    		}
    	}
    	
    	$mises = $serviceMises->recuperationMises($usr->getId());

    	return $this->render('asudreCDM14Bundle:Matchs:index.html.twig', array('matchs' => $matchs, 'mises' => $mises, 'cotes' => $cotes));
    	
	}
}
