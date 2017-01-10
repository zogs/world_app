<?php

namespace Zogs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Ws\MailerBundle\Entity\Settings as wsMailerSettings;
/**
 * @ORM\Entity
 * @ORM\Table(name="users_settings")
 */
class Settings
{ 
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    

    public function __construct()
    {
        //$this->ws_mailer_settings = new wsMailerSettings();
    }

    public function setData($data)
    {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)) $this->$key = $value;
        }
    }

    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * is email authorized
     *
     * @param Ws\MailerBundle\Entity\Settings const
     * @return boolean
     */

    public function isAuthorizedEmail($setting)
    {
        return $this->ws_mailer_settings->isAuthorized($setting);
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
     * Set user
     *
     * @param string $user
     * @return Settings
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

}
