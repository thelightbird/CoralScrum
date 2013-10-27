<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\TaskRepository")
 */
class Task
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
     * @ORM\ManyToOne(targetEntity="CoralScrum\MainBundle\Entity\UserStory", inversedBy="task")
     **/
    private $userStory;

    /**
     * @ORM\ManyToMany(targetEntity="CoralScrum\UserBundle\Entity\User")
     * @ORM\JoinTable(name="Task_User",
     *          joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *          )
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="CoralScrum\MainBundle\Entity\Task")
     * @ORM\JoinTable(name="Task_Dependency",
     *          joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="dependency_id", referencedColumnName="id")}
     *          )
     */
    private $dependency;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=30)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isBug", type="boolean")
     */
    private $isBug;

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=255, nullable=true)
     */
    private $commit;


    public function __toString()
    {
        return "#".$this->id.": ".$this->title;
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
     * Set title
     *
     * @param string $title
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Task
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Task
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Task
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set isBug
     *
     * @param boolean $isBug
     * @return Task
     */
    public function setIsBug($isBug)
    {
        $this->isBug = $isBug;
    
        return $this;
    }

    /**
     * Get isBug
     *
     * @return boolean 
     */
    public function getIsBug()
    {
        return $this->isBug;
    }

    /**
     * Set commit
     *
     * @param string $commit
     * @return Task
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    
        return $this;
    }

    /**
     * Get commit
     *
     * @return string 
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dependency = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->isBug = false;
    }

    /**
     * Set userStory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userStory
     * @return Task
     */
    public function setUserStory(\CoralScrum\MainBundle\Entity\UserStory $userStory = null)
    {
        $this->userStory = $userStory;
    
        return $this;
    }

    /**
     * Get userStory
     *
     * @return \CoralScrum\MainBundle\Entity\UserStory 
     */
    public function getUserStory()
    {
        return $this->userStory;
    }

    /**
     * Add user
     *
     * @param \CoralScrum\UserBundle\Entity\User $user
     * @return Task
     */
    public function addUser(\CoralScrum\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \CoralScrum\UserBundle\Entity\User $user
     */
    public function removeUser(\CoralScrum\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add dependency
     *
     * @param \CoralScrum\MainBundle\Entity\Task $dependency
     * @return Task
     */
    public function addDependency(\CoralScrum\MainBundle\Entity\Task $dependency)
    {
        $this->dependency[] = $dependency;
    
        return $this;
    }

    /**
     * Remove dependency
     *
     * @param \CoralScrum\MainBundle\Entity\Task $dependency
     */
    public function removeDependency(\CoralScrum\MainBundle\Entity\Task $dependency)
    {
        $this->dependency->removeElement($dependency);
    }

    /**
     * Get dependency
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDependency()
    {
        return $this->dependency;
    }
}