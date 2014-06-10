<?php

namespace asudre\CreationCDMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

class CreationGroupesController extends Controller
{
	
    public function indexAction()
    {
    	
    	$serviceCreationEquipes = $this->container->get('asudre_creation_cdm.equipes');
    	
    	// Récupération en base du nombre d'équipes créé et du nombre de groupe
    	$nbr = $serviceCreationEquipes->getNombreEquipesEtGroupes();
    	
        return $this->render('asudreCreationCDMBundle:CreationGroupes:index.html.twig', array('nbrEquipes' => $nbr[0][1], 'nbrGroupes' => $nbr[0][2]));
    }
    
    /**
     * Récupération des anciennes et nouvelles valeurs du nombre de groupes et d'équipes et mise à jour de la base
     */
    public function sauvegardeAction() {

    	$serviceCreationEquipes = $this->container->get('asudre_creation_cdm.equipes');
    	$serviceCreationMatchs = $this->container->get('asudre_creation_cdm.matchs');
    	
    	$request = $this->getRequest();
    	$nbGroupesAvant = $request->request->get('nbGroupesAvant');
    	$nbEquipesAvant = $request->request->get('nbEquipesAvant');
    	$nbGroupes = $request->request->get('nbGroupes');
    	$nbEquipes = $request->request->get('nbEquipes');
    	
    	if($nbEquipes != $nbEquipesAvant || $nbGroupes != $nbGroupesAvant) {
    		
    		$listeGroupes;
    		$nbEquipesParGroupe;
    		
    		if(is_numeric($nbEquipes) && is_numeric($nbGroupes) && is_int($nbEquipes/1) && is_int($nbGroupes/1) && is_int($nbEquipes / $nbGroupes)) {
    		
    			$nbEquipesParGroupe = $nbEquipes / $nbGroupes;
    		
    			// Suppression de toutes les équipes et tous les matchs de la base et enregistrement de la nouvelle structure de groupes
    			$serviceCreationMatchs->supprimerTous();
    			$serviceCreationEquipes->supprimerToutes();
    			$serviceCreationEquipes->creationEquipesAVide($nbEquipesParGroupe, $nbGroupes);
    		
    		}
    		else {
    			return new Response("Veuillez entrer des valeurs entières et pour lesquelles le nombre d'équipes par groupe est entier.");
    		}

    	}

    	// Redirection vers la modification des équipes
    	return $this->redirect( $this->generateUrl('asudre_creation_equipes') );
    }
}
