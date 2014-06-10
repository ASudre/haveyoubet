<?php

namespace asudre\CreationCDMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use asudre\CDM14Bundle\Entity\Matchs;
use asudre\CDM14Bundle\Entity\Equipes;
use asudre\CreationCDMBundle\Form\Type\MatchsType;
use asudre\CreationCDMBundle\Form\Type\MatchsArrayType;
use asudre\CreationCDMBundle\Form\Entity\MatchsCollection;

class CreationMatchsController extends Controller
{
    
    /**
     * Affichage du formulaire permettant à utilisateur de valider les équipes
     */
    public function indexAction()
    {

    	$serviceCreationMatchs = $this->container->get('asudre_creation_cdm.matchs');
    	
    	// Création du formulaire
    	$collectionMatchs = $this->getCollectionMatchs();
    	$form = $this->createForm(new MatchsArrayType(), $collectionMatchs);
    
    	// Récupération de la requête
    	$request = $this->getRequest();
    	 
    	// process the form on POST
    	if ($request->isMethod('POST')) {
    		$form->bind($request);
    		 
    		//     		if ($form->isValid()) {
    		 
    		$serviceCreationMatchs->sauvegardeMatchs($collectionMatchs->getMatchs());
    		 
     		// Redirection vers la modification des matchs
     		return $this->redirect( $this->generateUrl('asudre_creation_matchs') );
    		//     		}
    	}
    	 
    	// Affichage
    	return $this->render('asudreCreationCDMBundle:CreationMatchs:index.html.twig', array(
    			'form' => $form->createView(),
    	));
    
    }
    
    /**
     * Création du formulaire permettant la création / modification des équipes
     * @return unknown
     */
    private function getCollectionMatchs() {
    	$serviceCreationMatchs = $this->container->get('asudre_creation_cdm.matchs');
    	 
    	// Récupération des matchs stockés en base
    	$matchs = $serviceCreationMatchs->recuperationCollectionMatchs();
    	
    	$array = new MatchsCollection();
    
    	$array->setMatchs($matchs);
    	 
    	return $array;
    
    }
    
}
