<?php

namespace asudre\CDM14Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matchs
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="asudre\CDM14Bundle\Entity\MatchsRepository")
 */
class Matchs
{
	
	const ERREUR_MATCH_NON_COMMENCE = -1;
	
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
     * @ORM\Column(name="groupe", type="string", length=10)
     */
    private $groupe;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\Equipes")
     * @ORM\JoinColumn(name="equipe1_id", referencedColumnName="id")
     */
    private $equipe1;
    
    /**
     * @ORM\ManyToOne(targetEntity="asudre\CDM14Bundle\Entity\Equipes")
     * @ORM\JoinColumn(name="equipe2_id", referencedColumnName="id")
     */
    private $equipe2;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $date;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="scoreEq1", type="integer", nullable=true, options={"default" = null})
     */
    private $scoreEq1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="scoreEq2", type="integer", nullable=true, options={"default" = null})
     */
    private $scoreEq2;

    /**
     * @var integer
     *
     * @ORM\Column(name="idStade", type="integer", nullable=true)
     */
    private $idStade;

    /**
     * @var integer
     *
     * @ORM\Column(name="miseMax", type="integer", nullable=true)
     */
    private $miseMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="idCompetition", type="integer", nullable=true)
     */
    private $idCompetition;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estPhaseFinale", type="boolean", nullable=true)
     */
    private $estPhaseFinale = false;
    
    private $estMatchJoue = false;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Matchs
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
     * Set idStade
     *
     * @param integer $idStade
     * @return Matchs
     */
    public function setIdStade($idStade)
    {
        $this->idStade = $idStade;
    
        return $this;
    }

    /**
     * Get idStade
     *
     * @return integer 
     */
    public function getIdStade()
    {
        return $this->idStade;
    }

    /**
     * Set idCompetition
     *
     * @param integer $idCompetition
     * @return Matchs
     */
    public function setIdCompetition($idCompetition)
    {
        $this->idCompetition = $idCompetition;
    
        return $this;
    }

    /**
     * Get idCompetition
     *
     * @return integer 
     */
    public function getIdCompetition()
    {
        return $this->idCompetition;
    }

    /**
     * Set estPhaseFinale
     *
     * @param boolean $estPhaseFinale
     * @return Matchs
     */
    public function setEstPhaseFinale($estPhaseFinale)
    {
        $this->estPhaseFinale = $estPhaseFinale;
    
        return $this;
    }

    /**
     * Get estPhaseFinale
     *
     * @return boolean 
     */
    public function getEstPhaseFinale()
    {
        return $this->estPhaseFinale;
    }
    
    /**
     * Set estPhaseFinale
     *
     * @param boolean $estPhaseFinale
     * @return Matchs
     */
    public function setEstMatchJoue($estMatchJoue)
    {
    	$this->estMatchJoue = $estMatchJoue;
    
    	return $this;
    }
    
    /**
     * Get EstMatchJoue
     *
     * @return boolean
     */
    public function getEstMatchJoue()
    {
    	return $this->estMatchJoue;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     * @return Matchs
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
     * Set miseMax
     *
     * @param integer $miseMax
     * @return Matchs
     */
    public function setMiseMax($miseMax)
    {
        $this->miseMax = $miseMax;
    
        return $this;
    }

    /**
     * Get miseMax
     *
     * @return integer 
     */
    public function getMiseMax()
    {
        return $this->miseMax;
    }


    /**
     * Set equipe1
     *
     * @param \asudre\CDM14Bundle\Entity\Equipes $equipe1
     * @return Matchs
     */
    public function setEquipe1(\asudre\CDM14Bundle\Entity\Equipes $equipe1 = null)
    {
        $this->equipe1 = $equipe1;
    
        return $this;
    }

    /**
     * Get equipe1
     *
     * @return \asudre\CDM14Bundle\Entity\Equipes 
     */
    public function getEquipe1()
    {
        return $this->equipe1;
    }

    /**
     * Set equipe2
     *
     * @param \asudre\CDM14Bundle\Entity\Equipes $equipe2
     * @return Matchs
     */
    public function setEquipe2(\asudre\CDM14Bundle\Entity\Equipes $equipe2 = null)
    {
        $this->equipe2 = $equipe2;
    
        return $this;
    }

    /**
     * Get equipe2
     *
     * @return \asudre\CDM14Bundle\Entity\Equipes 
     */
    public function getEquipe2()
    {
        return $this->equipe2;
    }
    
    public function getSontScoresEntres() {
    	return $scoreEq1 != null && $scoreEq2 != null ? true : false; 
    }
    
    /**
     * Set scoreEq1
     *
     * @param integer $scoreEq1
     * @return Matchs
     */
    public function setScoreEq1($scoreEq1)
    {
    	$this->scoreEq1 = $scoreEq1;
    
    	return $this;
    }
    
    /**
     * Get scoreEq1
     *
     * @return integer
     */
    public function getScoreEq1()
    {
    	return $this->scoreEq1;
    }
    
    /**
     * Set scoreEq2
     *
     * @param integer $scoreEq2
     * @return Matchs
     */
    public function setScoreEq2($scoreEq2)
    {
    	$this->scoreEq2 = $scoreEq2;
    
    	return $this;
    }
    
    /**
     * Get scoreEq2
     *
     * @return integer
     */
    public function getScoreEq2()
    {
    	return $this->scoreEq2;
    }
}