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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="userproject")
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
     * @ORM\Column(name="isAccept", type="boolean")
     */
    private $isAccept;

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
}
