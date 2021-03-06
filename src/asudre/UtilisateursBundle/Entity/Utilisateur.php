<?php

namespace asudre\UtilisateursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="asudre\UtilisateursBundle\Entity\UtilisateurRepository")
 */
class Utilisateur extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var float
     *
     * @ORM\Column(name="cagnotte", type="float", options={"default" = 10000})
     */
    private $cagnotte;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $dateInscription;
    
    /**
     * @ORM\ManyToMany(targetEntity="asudre\CDM14Bundle\Entity\GroupesJoueurs", inversedBy="membres")
     */
    private $groupesJoueurs;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=4)
     */
    private $langue;

    /**
     * Constructor
     */
    public function __construct() {
		parent::__construct();
		$this->dateInscription = new \DateTime("now");
		$this->cagnotte = 10000;

		$this->groupesJoueurs = new \Doctrine\Common\Collections\ArrayCollection();
	}
    
    /**
     * Set cagnotte
     *
     * @param float $cagnotte
     * @return Utilisateur
     */
    public function setCagnotte($cagnotte)
    {
        $this->cagnotte = $cagnotte;
    
        return $this;
    }

    /**
     * Get cagnotte
     *
     * @return float 
     */
    public function getCagnotte()
    {
        return $this->cagnotte;
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
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     * @return Utilisateur
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
    
        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Add groupesJoueurs
     *
     * @param \asudre\CDM14Bundle\Entity\GroupesJoueurs $groupesJoueurs
     * @return Utilisateur
     */
    public function addGroupesJoueur(\asudre\CDM14Bundle\Entity\GroupesJoueurs $groupesJoueurs)
    {
        $this->groupesJoueurs[] = $groupesJoueurs;
    
        return $this;
    }

    /**
     * Remove groupesJoueurs
     *
     * @param \asudre\CDM14Bundle\Entity\GroupesJoueurs $groupesJoueurs
     */
    public function removeGroupesJoueur(\asudre\CDM14Bundle\Entity\GroupesJoueurs $groupesJoueurs)
    {
        $this->groupesJoueurs->removeElement($groupesJoueurs);
    }

    /**
     * Get groupesJoueurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupesJoueurs()
    {
        return $this->groupesJoueurs;
    }

    /**
     * Set langue
     *
     * @param string $langue
     * @return Utilisateur
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;
    
        return $this;
    }

    /**
     * Get langue
     *
     * @return string 
     */
    public function getLangue()
    {
        return $this->langue;
    }
}