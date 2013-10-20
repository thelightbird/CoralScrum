<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Developer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\DeveloperRepository")
 */
class Developer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /** @ORM\OneToMany(targetEntity="DeveloperProject", mappedBy="developer") */
    private $developerproject;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=30)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="accountType", type="string", length=30)
     */
    private $accountType;

    /**
     * @var string
     *
     * @ORM\Column(name="registrationIp", type="string", length=15)
     */
    private $registrationIp;

    /**
     * @var string
     *
     * @ORM\Column(name="lastConnectionIp", type="string", length=15)
     */
    private $lastConnectionIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastConnectedDate", type="datetime")
     */
    private $lastConnectedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationDate", type="datetime")
     */
    private $registrationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=30)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=50)
     */
    private $picture;


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
     * Set username
     *
     * @param string $username
     * @return Developer
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Developer
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Developer
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set accountType
     *
     * @param string $accountType
     * @return Developer
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    
        return $this;
    }

    /**
     * Get accountType
     *
     * @return string 
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set registrationIp
     *
     * @param string $registrationIp
     * @return Developer
     */
    public function setRegistrationIp($registrationIp)
    {
        $this->registrationIp = $registrationIp;
    
        return $this;
    }

    /**
     * Get registrationIp
     *
     * @return string 
     */
    public function getRegistrationIp()
    {
        return $this->registrationIp;
    }

    /**
     * Set lastConnectionIp
     *
     * @param string $lastConnectionIp
     * @return Developer
     */
    public function setLastConnectionIp($lastConnectionIp)
    {
        $this->lastConnectionIp = $lastConnectionIp;
    
        return $this;
    }

    /**
     * Get lastConnectionIp
     *
     * @return string 
     */
    public function getLastConnectionIp()
    {
        return $this->lastConnectionIp;
    }

    /**
     * Set lastConnectedDate
     *
     * @param \DateTime $lastConnectedDate
     * @return Developer
     */
    public function setLastConnectedDate($lastConnectedDate)
    {
        $this->lastConnectedDate = $lastConnectedDate;
    
        return $this;
    }

    /**
     * Get lastConnectedDate
     *
     * @return \DateTime 
     */
    public function getLastConnectedDate()
    {
        return $this->lastConnectedDate;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     * @return Developer
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    
        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Developer
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
     * @return Developer
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
     * Set picture
     *
     * @param string $picture
     * @return Developer
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
