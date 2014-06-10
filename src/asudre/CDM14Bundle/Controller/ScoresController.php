<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ScoresController extends Controller
{
	/**
	 * Affichage de la liste des matchs pour lesquels va pouvoir miser l'utilisateur
	 */
    public function indexAction()
    {
    	
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "scores");
    	
		$serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	
    	$matchs = $serviceMatchs->recuperationMatchsOrdDate();
    	
    	foreach ($matchs as $match) {
    		// Affichage bouton valider grisé si le match commencé
    		if($match->getDate() <= new \DateTime("now")) {
    			$match->setEstMatchJoue(true);
    		}
    	}

    	return $this->render('asudreCDM14Bundle:Scores:index.html.twig', array('matchs' => $matchs));
    	 
	}
}
