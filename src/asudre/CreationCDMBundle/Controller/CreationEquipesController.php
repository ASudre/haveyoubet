<?php

namespace asudre\CreationCDMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use asudre\CDM14Bundle\Entity\Equipes;
use asudre\CreationCDMBundle\Form\Type\EquipesType;
use asudre\CreationCDMBundle\Form\Type\EquipesArrayType;
use asudre\CreationCDMBundle\Form\Entity\EquipesCollection;
use Symfony\Component\HttpFoundation\Response;

class CreationEquipesController extends Controller
{

	/**
	 * Affichage du formulaire permettant à utilisateur de valider les équipes
	 */
    public function indexAction()
    {

    	$serviceCreationEquipes = $this->container->get('asudre_creation_cdm.equipes');

    	// Création du formulaire
    	$collectionEquipes = $this->getCollectionEquipes();
    	$form = $this->createForm(new EquipesArrayType(), $collectionEquipes);

    	// Récupération de la requête
    	$request = $this->getRequest();
    	
    	// process the form on POST
    	if ($request->isMethod('POST')) {
    		$form->bind($request);
    	
//     		if ($form->isValid()) {
    		 
    			$serviceCreationEquipes->sauvegardeEquipesMatchs($collectionEquipes->getEquipes());
    			
    			// Redirection vers la modification des matchs
		    	return $this->redirect( $this->generateUrl('asudre_creation_matchs') );
//     		}
    	}
    	
    	// Affichage
    	return $this->render('asudreCreationCDMBundle:CreationEquipes:index.html.twig', array(
    			'form' => $form->createView(),
    	));

    }
    
    /**
     * Création du formulaire permettant la création / modification des équipes
     * @return unknown
     */
    private function getCollectionEquipes() {
    	$serviceCreationEquipes = $this->container->get('asudre_creation_cdm.equipes');
    	
    	// Récupération des équipes stockées en base
    	$equipes = $serviceCreationEquipes->recuperationEquipesOrdonneesParGroupes();
    	
     	$array = new EquipesCollection();
     	
     	$array->setEquipes($equipes);
    	
    	return $array;

    }
    
}
