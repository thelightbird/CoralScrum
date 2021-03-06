<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\ProjectRepository")
 */
class Project
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
      * @ORM\OneToMany(targetEntity="CoralScrum\MainBundle\Entity\UserProject", mappedBy="project", cascade={"remove"})
      */
    private $userproject;

    /**
     * @ORM\OneToMany(targetEntity="CoralScrum\MainBundle\Entity\UserStory", mappedBy="project", cascade={"remove"})
     */
    private $userStory;


    /**
     * @ORM\OneToMany(targetEntity="CoralScrum\MainBundle\Entity\Sprint", mappedBy="project", cascade={"remove"})
     */
    private $sprint;

    /**
     * @ORM\ManyToOne(targetEntity="CoralScrum\UserBundle\Entity\User")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublic", type="boolean")
     */
    private $isPublic;

    /**
     * @var string
     *
     * @ORM\Column(name="repository", type="string", length=255, nullable=true)
     */
    private $repository;


    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userproject = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isPublic = false;
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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Project
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    
        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set repository
     *
     * @param string $repository
     * @return Project
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    
        return $this;
    }

    /**
     * Get repository
     *
     * @return string 
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Add userproject
     *
     * @param \CoralScrum\MainBundle\Entity\UserProject $userproject
     * @return Project
     */
    public function addUserproject(\CoralScrum\MainBundle\Entity\UserProject $userproject)
    {
        $this->userproject[] = $userproject;
    
        return $this;
    }

    /**
     * Remove userproject
     *
     * @param \CoralScrum\MainBundle\Entity\UserProject $userproject
     */
    public function removeUserproject(\CoralScrum\MainBundle\Entity\UserProject $userproject)
    {
        $this->userproject->removeElement($userproject);
    }

    /**
     * Get userproject
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserproject()
    {
        return $this->userproject;
    }

    /**
     * Set owner
     *
     * @param \CoralScrum\UserBundle\Entity\User $owner
     * @return Project
     */
    public function setOwner(\CoralScrum\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \CoralScrum\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add userStory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userStory
     * @return Project
     */
    public function addUserStory(\CoralScrum\MainBundle\Entity\UserStory $userStory)
    {
        $this->userStory[] = $userStory;
    
        return $this;
    }

    /**
     * Remove userStory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userStory
     */
    public function removeUserStory(\CoralScrum\MainBundle\Entity\UserStory $userStory)
    {
        $this->userStory->removeElement($userStory);
    }

    /**
     * Get userStory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserStory()
    {
        return $this->userStory;
    }

    /**
     * Add sprint
     *
     * @param \CoralScrum\MainBundle\Entity\Sprint $sprint
     * @return Project
     */
    public function addSprint(\CoralScrum\MainBundle\Entity\Sprint $sprint)
    {
        $this->sprint[] = $sprint;
    
        return $this;
    }

    /**
     * Remove sprint
     *
     * @param \CoralScrum\MainBundle\Entity\Sprint $sprint
     */
    public function removeSprint(\CoralScrum\MainBundle\Entity\Sprint $sprint)
    {
        $this->sprint->removeElement($sprint);
    }

    /**
     * Get sprint
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSprint()
    {
        return $this->sprint;
    }
}