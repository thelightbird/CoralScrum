<?php

namespace CoralScrum\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoralScrum\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /** @ORM\OneToMany(targetEntity="CoralScrum\MainBundle\Entity\UserProject", mappedBy="user") */
    private $userproject;

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
     * @ORM\Column(name="picture", type="string", length=50, nullable=true)
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Set registrationIp
     *
     * @param string $registrationIp
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Set picture
     *
     * @param string $picture
     * @return User
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

    public function __construct() {
        //$this->picture = null;
        $this->registrationDate = new \Datetime();
        //$this->registrationIp = $container->get('request')->getClientIp();
        //$this->lastConnectionIp = $container->get('request')->getClientIp();
        $this->registrationIp = "127.0.0.1";
        $this->lastConnectionIp = "127.0.0.1";
        $this->lastConnectedDate = new \Datetime();
        parent::__construct();
    }
}
