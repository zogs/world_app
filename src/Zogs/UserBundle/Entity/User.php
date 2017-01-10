<?php

namespace Zogs\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Zogs\UserBundle\Entity\Avatar;
use Zogs\UserBundle\Entity\Settings;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Zogs\UserBundle\Entity\UserRepository")
 * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $civc_id = null;
  
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname = '';

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastname = '';
    
    /**
     * @ORM\OneToOne(targetEntity="Zogs\UserBundle\Entity\Avatar", fetch="EAGER", cascade={"all"})
     * @ORM\JoinColumn(nullable=true, name="avatar_id", referencedColumnName="id")
     */
    private $avatar = null;
 
    /**
     * @ORM\Column(type="date")
     */
    private $register_since = '';


    /**
    * @ORM\Column(type="integer", nullable=true)
    */
    private $updatedCount = '';


    public function __construct()
    {
        parent::__construct();       
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(empty($this->register_since)) $this->register_since = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function countProfileUpdated()
    {
        $this->updatedCount++;
    }
   
    /**
     * @ORM\PrePersist
     */
    public function setDefaultAvatar()
    {        
        if(!isset($this->avatar)) $this->avatar = new Avatar();
    }


    

    /**
     * Set civc_id
     *
     * @param integer $civcId
     * @return User
     */
    public function setCivcId($civcId)
    {
        $this->civc_id = $civcId;

        return $this;
    }

    /**
     * Get civc_id
     *
     * @return integer 
     */
    public function getCivcId()
    {
        return $this->civc_id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set register_since
     *
     * @param \DateTime $registerSince
     * @return User
     */
    public function setRegisterSince($registerSince)
    {
        $this->register_since = $registerSince;

        return $this;
    }

    /**
     * Get register_since
     *
     * @return \DateTime 
     */
    public function getRegisterSince()
    {
        return $this->register_since;
    }

    /**
     * Set updatedCount
     *
     * @param integer $updatedCount
     * @return User
     */
    public function setUpdatedCount($updatedCount)
    {
        $this->updatedCount = $updatedCount;

        return $this;
    }

    /**
     * Get updatedCount
     *
     * @return integer 
     */
    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    /**
     * Set avatar
     *
     * @param \Zogs\UserBundle\Entity\Avatar $avatar
     * @return User
     */
    public function setAvatar(\Zogs\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Zogs\UserBundle\Entity\Avatar 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set date_cumul_begin
     *
     * @param \DateTime $date
     * @return User
     */
    public function setDateCumulBegin($date)
    {
        $this->date_cumul_begin = $date;

        return $this;
    }

    /**
     * Get date_cumul_begin
     *
     * @return \DateTime 
     */
    public function getDateCumulBegin()
    {
        return $this->date_cumul_begin;
    }


    /**
     * Set date_cumul_end
     *
     * @param \DateTime $date
     * @return User
     */
    public function setDateCumulEnd($date)
    {
        $this->date_cumul_end = $date;

        return $this;
    }

    /**
     * Get date_cumul_end
     *
     * @return \DateTime 
     */
    public function getDateCumulEnd()
    {
        return $this->date_cumul_end;
    }

    /**
     * Set date_debour_CH
     *
     * @param \DateTime $dateDebourCH
     * @return User
     */
    public function setDateDebourCH($dateDebourCH)
    {
        $this->date_debour_CH = $dateDebourCH;

        return $this;
    }

    /**
     * Get date_debour_CH
     *
     * @return \DateTime 
     */
    public function getDateDebourCH()
    {
        return $this->date_debour_CH;
    }

    /**
     * Set date_debour_PN
     *
     * @param \DateTime $dateDebourPN
     * @return User
     */
    public function setDateDebourPN($dateDebourPN)
    {
        $this->date_debour_PN = $dateDebourPN;

        return $this;
    }

    /**
     * Get date_debour_PN
     *
     * @return \DateTime 
     */
    public function getDateDebourPN()
    {
        return $this->date_debour_PN;
    }

    /**
     * Set date_debour_MN
     *
     * @param \DateTime $dateDebourMN
     * @return User
     */
    public function setDateDebourMN($dateDebourMN)
    {
        $this->date_debour_MN = $dateDebourMN;

        return $this;
    }

    /**
     * Get date_debour_MN
     *
     * @return \DateTime 
     */
    public function getDateDebourMN()
    {
        return $this->date_debour_MN;
    }

    /**
     * Set basemap
     *
     * @param string $basemap
     * @return User
     */
    public function setBasemap($basemap)
    {
        $this->basemap = $basemap;

        return $this;
    }

    /**
     * Get basemap
     *
     * @return string 
     */
    public function getBasemap()
    {
        return $this->basemap;
    }
}
