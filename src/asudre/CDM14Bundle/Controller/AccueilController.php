<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AccueilController extends Controller
{
	/**
	 * Affichage de la liste des matchs pour lesquels va pouvoir miser l'utilisateur
	 */
    public function indexAction()
    {
		// Récupération de l'utilisateur connecté
    	$usr= $this->get('security.context')->getToken()->getUser();
    	
    	$serviceMises = $this->container->get('asudre.serviceMises');
    	
    	$sommeMises = $serviceMises->getSommeMises($usr->getId());
    	
    	// Récupération de la requête
    	$request = $this->getRequest();
    	
    	if($usr->getLangue() == "" || $usr->getLangue() == "fr") {
    		$langue = 'fr';
    	}
    	elseif($usr->getLangue() == "en") {
    		$langue = 'en';
    	}
    	else {
    		$langue = 'fr';
    	}
    	
    	$request->getSession()->set('_locale', $usr->getLangue());
    	
   		$request->getSession()->set("sommeMises", $sommeMises);
    	
		return $this->redirect( $this->generateUrl('asudre_matchs') );
	}
	
}
