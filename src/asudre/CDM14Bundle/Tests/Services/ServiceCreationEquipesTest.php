<?php

namespace asudre\CDM14\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceCreationEquipesTest extends WebTestCase
{
    public function testRecuperationEquipesParGroupe()
    {
    	$serviceCreationEquipes = $this->container->get('asudre_creation_cdm.equipes');
    	$tabEquipesParGroupe = $serviceCreationEquipes->recuperationEquipesParGroupe();
    	
    	foreach ($tabEquipesParGroupe as $gr => $eq) {
    	
    		foreach ($eq as $id => $equipe2) {
	    		
	    		echo $groupe;
	    		echo $equipe2->getId();
				
	    	}
    		 
    	}
    	
    }
}
