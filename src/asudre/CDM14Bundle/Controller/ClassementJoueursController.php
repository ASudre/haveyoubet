<?php

namespace asudre\CDM14Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClassementJoueursController extends Controller
{
	/**
	 * Affichage de la liste des matchs pour lesquels va pouvoir miser l'utilisateur
	 */
    public function indexAction()
    {
    	
    	// gestion menu gauche
    	$request = $this->getRequest();
    	$request->getSession()->set("menuGauche", "classementJoueurs");
    	
    	$idMatch = $request->request->get("selectMatch");
    	$idGroupe = $request->request->get("selectGroupe");
    	
    	if($idMatch == null || "" == $idMatch) {
	    	$idMatch = $request->getSession()->get("matchClassement");
    	}
    	
    	if($idGroupe == null || "" == $idGroupe) {
    		$idGroupe = $request->getSession()->get("groupeClassement");
    	}
    	
    	$request->getSession()->set("matchClassement", $idMatch);
    	$request->getSession()->set("groupeClassement", $idGroupe);
    	
		// Récupération de l'utilisateur connecté
    	$usr = $this->get('security.context')->getToken()->getUser();
    	
    	$serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
    	$serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	$serviceGroupes = $this->container->get('asudre_serviceGroupes');
    	 
    	// Génération de la liste des matchs
    	$matchs = $serviceMatchs->recuperationMatchsOrdDate();
    	
    	// Génération de la liste des groupes
    	$groupes = $usr->getGroupesJoueurs()->toArray();

    	$matchAffiche = null;
    	$dernierMatch = null;
    	
    	foreach ($matchs as $index=>$match) {
    		
    		// On enlève qui ne sont pas encore joué
    		if($match->getDate() > new \DateTime("now")) {
    			unset($matchs[$index]);
    		}
    		else {
    			
    			// Vérifie si le score du match a été entré par l'admin
    			if($match->getScoreEq1() !== null && $match->getScoreEq2() !== null) {
    				$match->setEstMatchJoue(true);
    				
	    			// à la fin de la boucle, contiendra le dernier match dont le score a été validé
		   			$dernierMatch = $match;
    			}

    			// Si on n'est pas encore tombé sur le match à afficher
    			if($matchAffiche == null && $match->getId() == $idMatch) {
    				$matchAffiche = $match;
    			}
    			
    		}
    	}
    	
    	if($matchAffiche == null) {
    		$matchAffiche = $dernierMatch;
    	}
    	
    	$utilisateurs = array();
    	$idGroupeAffiche = $idGroupe;
    	$idMatchAffiche = null;
    	
    	if($matchAffiche != null) {
    		
    		$idMatchAffiche = $matchAffiche->getId();
    		
    		if(!$matchAffiche->getEstMatchJoue()) {
    			return $this->render('asudreCDM14Bundle:ClassementJoueurs:index.html.twig', array('groupes' => $groupes, 'matchs' => $matchs, 'idMatchAffiche' => $idMatchAffiche, 'idGroupeAffiche' => $idGroupeAffiche, 'msgErreur' => 'Pas de score en base pour ce match.'));
    		}
    		else {
    		
	    		if($idGroupeAffiche != null && $idGroupeAffiche != 0) {
	    			
	    			$estGroupeUtilisateur = $serviceGroupes->estGroupeUtilisateur($idGroupeAffiche, $usr->getId());
	    			
	    			if(!$estGroupeUtilisateur) {
	    				return $this->render('asudreCDM14Bundle:ClassementJoueurs:index.html.twig', array('groupes' => $groupes, 'matchs' => $matchs, 'idMatchAffiche' => $idMatchAffiche, 'idGroupeAffiche' => $idGroupeAffiche, 'msgErreur' => 'Vous n\'appartenez pas à ce groupe.'));
	    			}
	    			
		    		$utilisateurs = $serviceUtilisateurs->getUtilisateursOrdCagnotteParGroupe($matchAffiche, $idGroupeAffiche);
	    		}
	    		else {
	    			$idGroupeAffiche = 0;
	    			$utilisateurs = $serviceUtilisateurs->recuperationUtilisateursOrdCagnotte($matchAffiche);
	    		}
	    	}
    	}
    	
    	return $this->render('asudreCDM14Bundle:ClassementJoueurs:tableau.html.twig', array('groupes' => $groupes, 'matchs' => $matchs, 'utilisateurs' => $utilisateurs, 'idMatchAffiche' => $idMatchAffiche, 'idGroupeAffiche' => $idGroupeAffiche, 'typeAffiche' => 'tableau'));
	
    }
    
    /**
     * Méthode d'affichage du graphique
     */
    public function graphiqueAction() {
    	
    	$serviceUtilisateurs = $this->container->get('asudre.serviceUtilisateurs');
    	$serviceMatchs = $this->container->get('asudre.serviceMatchs');
    	
    	$match = $serviceMatchs->getDernierMatchJoue();
    	
    	if($match != null) {
    		$idMatchCourant = null;
    		$cagnotteInitiale = 10000;
    		// Les deux premières lignes sont le nom des joueurs et les cagnottes initiales
    		$indexNumMatch = 1;
    		
    		// contient les cagnottes pour chaque joueur et pour chaque match ordonné
    		$tableauCagnottes = array();
    		$tableauCagnottes[0][0] = 'Match';
    		$tableauCagnottes[1][0] = 0;
    		
    		// utilisateurs ordonnés par id
    		$utilisateurs = $serviceUtilisateurs->getUtilisateurs();
    		
    		// Initialisation du tableau
    		foreach ($utilisateurs as $index=>$joueur) {
    			
    			// on sauve le nom des joueurs
    			$tableauCagnottes[0][$joueur->getId()] = $joueur->getUsername();
    			// On initialise la cagnotte
    			$tableauCagnottes[1][$joueur->getId()] = $cagnotteInitiale;
    		}
    		
    		$joueursCagnottes = $serviceUtilisateurs->getUtilisateursGainsMatchsTous($match);
    		
    		$tmpJoueurs = null;
    		
     		// Crée le tableau en associant les utilisateurs à leurs cagnottes ordonnées par match
    		foreach ($joueursCagnottes as $index=>$gainJoueur) {
    			
    			// Pour chaque nouveau match
    			if($idMatchCourant != $gainJoueur['idMatch']) {

    				// On positionne les cagnottes des joueurs n'étant pas inscrit au match et absents du tableau $tableauCagnottes
    				if($tmpJoueurs != null) {
    					foreach ($tmpJoueurs as $id=>$username) {
    						$tableauCagnottes[$indexNumMatch][$id] = $tableauCagnottes[$indexNumMatch-1][$id];
    					}
    				}
    				
    				// Tableau temporaire permettant de mettre à jour la cagnotte des joueurs non inscrits à l'époque du match
    				$tmpJoueurs = $tableauCagnottes[0];
    				
    				// récupération du match courant
	    			$idMatchCourant = $gainJoueur['idMatch'];
	    			
	    			// Positionne du numéro du match en début de tableau
    				$tableauCagnottes[++$indexNumMatch][0] = $indexNumMatch - 1;

    			}
    			
   				$tableauCagnottes[$indexNumMatch][$gainJoueur['userId']] = round($gainJoueur['gain'] + $tableauCagnottes[$indexNumMatch-1][$gainJoueur['userId']], 2);
				unset($tmpJoueurs[$gainJoueur['userId']]);
    		}
    		
    	}
    	
    	var_dump($tableauCagnottes);
    	return new Response("");
    	
// 		// Suppression des index
//     	foreach ($tableauCagnottes as $index=>$tabMatch) {
//     		$tableauCagnottes[$index] = array_values($tabMatch);
//     	}

//     	return $this->render('asudreCDM14Bundle:ClassementJoueurs:graphique.html.twig', array('tableau' => json_encode($tableauCagnottes, false)));
	}

}
