<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\EquipesRepository")
 */
class Equipes
{
	
	const ID_NUL = 33;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="groupe", type="string", length=1)
     */
    private $groupe;
    
    /**
     * Set id
     *
     * @param integer
     * @return equipe
     */
    public function setId($id)
    {
    	$this->id = $id;
    	
    	return $this;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Equipes
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return Equipes
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    
        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     * @return Equipes
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
    
        return $this;
    }

    /**
     * Get groupe
     *
     * @return string 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Affichage de la classe
     * @return string
     */
    public function __toString() {
    	return "equipe : ".$this->getNom();
    }
}