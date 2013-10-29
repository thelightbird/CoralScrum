<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProject
 *
 * @ORM\Table(name="user_project")
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\UserProjectRepository")
 */
class UserProject
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
     * @var Project $project
     *
     * @ORM\ManyToOne(targetEntity="CoralScrum\MainBundle\Entity\Project", inversedBy="userproject")
     */
    private $project;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="CoralScrum\UserBundle\Entity\User", inversedBy="userproject")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="accountType", type="string", length=30)
     */
    private $accountType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAccept", type="boolean", nullable=true)
     */
    private $isAccept;



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
     * Set accountType
     *
     * @param string $accountType
     * @return UserProject
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
     * Set isAccept
     *
     * @param boolean $isAccept
     * @return UserProject
     */
    public function setIsAccept($isAccept)
    {
        $this->isAccept = $isAccept;
    
        return $this;
    }

    /**
     * Get isAccept
     *
     * @return boolean 
     */
    public function getIsAccept()
    {
        return $this->isAccept;
    }

    /**
     * Set project
     *
     * @param \CoralScrum\MainBundle\Entity\Project $project
     * @return UserProject
     */
    public function setProject(\CoralScrum\MainBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \CoralScrum\MainBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set user
     *
     * @param \CoralScrum\UserBundle\Entity\User $user
     * @return UserProject
     */
    public function setUser(\CoralScrum\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \CoralScrum\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}