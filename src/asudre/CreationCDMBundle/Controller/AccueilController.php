<?php

namespace asudre\CreationCDMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "competition");
    	
        return $this->render('asudreCreationCDMBundle:Accueil:index.html.twig');
    }
}
