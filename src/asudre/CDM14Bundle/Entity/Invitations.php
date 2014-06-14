<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitations
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\InvitationsRepository")
 */
class Invitations
{
	
	const OK = 0;
	const ERREUR_VALEURS_FORM = -1;
	const ERREUR_PSEUDONYME_INVALIDE = -2;
	const ERREUR_DEJA_DANS_GROUPE = -3;
	
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
     * @ORM\Column(name="courriel", type="string", length=255)
     */
    private $courriel;

    /**
     * @ORM\ManyToOne(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="invite_id", referencedColumnName="id")
     */
    private $invite;

    /**
     * @var string
     *
     * @ORM\Column(name="codeInscription", type="string", length=255)
     */
    private $codeInscription;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\UtilisateursBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="hote_id", referencedColumnName="id")
     */
    private $hote;

    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\GroupesJoueurs")
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     */
    private $groupe;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=4)
     */
    private $langue;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $date;

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
     * Set courriel
     *
     * @param string $courriel
     * @return Invitations
     */
    public function setCourriel($courriel)
    {
        $this->courriel = $courriel;
    
        return $this;
    }

    /**
     * Get courriel
     *
     * @return string 
     */
    public function getCourriel()
    {
        return $this->courriel;
    }

    /**
     * Set estUtilise
     *
     * @param boolean $estUtilise
     * @return Invitations
     */
    public function setEstUtilise($estUtilise)
    {
        $this->estUtilise = $estUtilise;
    
        return $this;
    }

    /**
     * Get estUtilise
     *
     * @return boolean 
     */
    public function getEstUtilise()
    {
        return $this->estUtilise;
    }

    /**
     * Set codeInscription
     *
     * @param string $codeInscription
     * @return Invitations
     */
    public function setCodeInscription($codeInscription)
    {
        $this->codeInscription = $codeInscription;
    
        return $this;
    }

    /**
     * Get codeInscription
     *
     * @return string 
     */
    public function getCodeInscription()
    {
        return $this->codeInscription;
    }

    /**
     * Set hote
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $hote
     * @return Invitations
     */
    public function setHote(\asudre\UtilisateursBundle\Entity\Utilisateur $hote = null)
    {
        $this->hote = $hote;
    
        return $this;
    }

    /**
     * Get hote
     *
     * @return \asudre\UtilisateursBundle\Entity\Utilisateur 
     */
    public function getHote()
    {
        return $this->hote;
    }

    /**
     * Set groupe
     *
     * @param \asudre\CDM14Bundle\Entity\GroupesJoueurs $groupe
     * @return Invitations
     */
    public function setGroupe(\asudre\CDM14Bundle\Entity\GroupesJoueurs $groupe = null)
    {
        $this->groupe = $groupe;
    
        return $this;
    }

    /**
     * Get groupe
     *
     * @return \asudre\CDM14Bundle\Entity\GroupesJoueurs 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Invitations
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
     * Set invite
     *
     * @param \asudre\UtilisateursBundle\Entity\Utilisateur $invite
     * @return Invitations
     */
    public function setInvite(\asudre\UtilisateursBundle\Entity\Utilisateur $invite = null)
    {
        $this->invite = $invite;
    
        return $this;
    }

    /**
     * Get invite
     *
     * @return \asudre\UtilisateursBundle\Entity\Utilisateur 
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * Set langue
     *
     * @param string $langue
     * @return Invitations
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