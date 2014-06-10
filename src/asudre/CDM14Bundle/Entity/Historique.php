<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historique
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\HistoriqueRepository")
 */
class historique
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="gain", type="float")
     */
    private $gain;
    
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
     * @ORM\ManyToOne(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\Matchs")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     */
    private $match;
    
    /**
     * Set match
     *
     * @param \asudre\CDM14Bundle\Entity\Matchs $match
     * @return Mises
     */
    public function setMatch(\asudre\CDM14Bundle\Entity\Matchs $match = null)
    {
    	$this->match = $match;
    
    	return $this;
    }
    
    /**
     * Get match
     *
     * @return \asudre\CDM14Bundle\Entity\Matchs
     */
    public function getMatch()
    {
    	return $this->match;
    }
    
    /**
     * Set utilisateur
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $utilisateur
     * @return Mises
     */
    public function setUtilisateur(\asudre\UtilisateursBundle\Entity\Utilisateur $utilisateur = null)
    {
    	$this->utilisateur = $utilisateur;
    
    	return $this;
    }
    
    /**
     * Get utilisateur
     *
     * @return \asudre\UtilisateursBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
    	return $this->utilisateur;
    }
    
    /**
     * Set gain
     *
     * @param float $gain
     * @return historique
     */
    public function setGain($gain)
    {
    	$this->gain = $gain;
    
    	return $this;
    }
    
    /**
     * Get gain
     *
     * @return float
     */
    public function getGain()
    {
    	return $this->gain;
    }
}
