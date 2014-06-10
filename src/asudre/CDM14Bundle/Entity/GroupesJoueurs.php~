<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * groupes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\GroupesJoueursRepository")
 */
class GroupesJoueurs
{
	
	const OK = 0;
	const ERREUR_NOM_NON_VALIDE = -1;
	const ERREUR_NOM_UTILISE = -2;
	const ERREUR_SAUVEGARDE_BASE = -3;
	
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
     * @ORM\Column(name="nomGroupe", type="string", length=255)
     */
    private $nomGroupe;

    /**
     * @ORM\ManyToMany(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur", mappedBy="groupesJoueurs")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $membres;

    /**
     * @ORM\ManyToOne(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="createur_id", referencedColumnName="id")
     */
    private $createur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $date;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
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
     * Set nomGroupe
     *
     * @param string $nomGroupe
     * @return groupes
     */
    public function setNomGroupe($nomGroupe)
    {
        $this->nomGroupe = $nomGroupe;
    
        return $this;
    }

    /**
     * Get nomGroupe
     *
     * @return string 
     */
    public function getNomGroupe()
    {
        return $this->nomGroupe;
    }
    

    /**
     * Add membres
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $membres
     * @return Groupes
     */
    public function addMembre(\asudre\UtilisateursBundle\Entity\Utilisateur $membres)
    {
        $this->membres[] = $membres;
    
        return $this;
    }

    /**
     * Remove membres
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $membres
     */
    public function removeMembre(\asudre\UtilisateursBundle\Entity\Utilisateur $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * Set createur
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $createur
     * @return Groupes
     */
    public function setCreateur(\asudre\UtilisateursBundle\Entity\Utilisateur $createur = null)
    {
        $this->createur = $createur;
    
        return $this;
    }

    /**
     * Get createur
     *
     * @return \asudre\UtilisateursBundle\Entity\Utilisateur 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Groupes
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
}