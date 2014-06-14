<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChangeLangueController extends Controller
{
	/**
	 * Change la langue de l'utilisateur
	 */
    public function indexAction()
    {
    	if('fr' == $this->get('session')->get('_locale')) {
			$this->get('session')->set('_locale', 'en');
    	}
    	else {
    		$this->get('session')->set('_locale', 'fr');
    	}
    	
		return $this->redirect( $this->generateUrl($this->getRequest()->query->get('redirect')) );
	}
	
}
