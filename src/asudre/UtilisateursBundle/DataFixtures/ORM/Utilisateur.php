<?php

namespace asudre\UtilisateursBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use asudre\UtilisateursBundle\Entity\Utilisateur;

class Utilisateur implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// Les noms d'utilisateurs à créer
		$noms = array('winzou', 'John', 'Talus');
		foreach ($noms as $i => $nom) {
			// On crée l'utilisateur
			$users[$i] = new Utilisateur;
			// Le nom d'utilisateur et le mot de passe sont identiques
			$users[$i]->setIdentifiant($nom);
			$users[$i]->setMotDePasse($nom);
			// Le sel et les rôles sont vides pour l'instant
			$users[$i]->setSalt('');
			$users[$i]->setRoles(array());
			// On le persiste
			$manager->persist($users[$i]);
		}
		// On déclenche l'enregistrement
		$manager->flush();
	}
}