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
class ReglesController extends Controller
{
	
	/**
	 * Affichage des règles
	 */
    public function indexAction()
    {
    	
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "regles");

    	return $this->render('asudreCDM14Bundle:Regles:index.html.twig');
    	
	}
	
}