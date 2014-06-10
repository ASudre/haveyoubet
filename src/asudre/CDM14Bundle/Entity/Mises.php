<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mises
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\MisesRepository")
 */
class Mises
{

	const OK = 0;
	const ERREUR_CAGNOTTE = -1;
	const ERREUR_CORRESPONDANCE_MATCH_EQUIPE = -2;
	const ERREUR_MATCH_COMMENCE = -3;
	const ERREUR_CAGNOTTE_TROP_FAIBLE = -4;
	const ERREUR_VALEURS_FORM = -5;
	
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
     * @ORM\Column(name="valeur", type="float")
     */
    private $valeur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\Equipes")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id")
     */
    private $equipe;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\Matchs")
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     */
    private $match;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;
    
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
     * Set valeur
     *
     * @param float $valeur
     * @return Mises
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    
        return $this;
    }

    /**
     * Get valeur
     *
     * @return float 
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Mises
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set equipe
     *
     * @param \asudre\CDM14Bundle\Entity\Equipes $equipe
     * @return Mises
     */
    public function setEquipe(\asudre\CDM14Bundle\Entity\Equipes $equipe = null)
    {
        $this->equipe = $equipe;
    
        return $this;
    }

    /**
     * Get equipe
     *
     * @return \asudre\CDM14Bundle\Entity\Equipes 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

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
}