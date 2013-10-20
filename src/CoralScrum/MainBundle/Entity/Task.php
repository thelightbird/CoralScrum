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
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="task")
     */
    private $dependency;

    /**
     * @ORM\ManyToMany(targetEntity="Task", inversedBy="dependency")
     * @ORM\JoinTable(name="Task_Dependency",
     *          joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="dependency_id", referencedColumnName="id")}
     *          )
     */
    private $task;

    /**
     * @var Project $project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="CoralScrum\UserBundle\Entity\User")
     * @ORM\JoinTable(name="Task_User",
     *          joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *          inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *          )
     */
    private $user;

    /**
     * @var UserStory $userStory
     *
     * @ORM\ManyToOne(targetEntity="UserStory")
     */
    private $userStory;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

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
     * @ORM\Column(name="endDate", type="date")
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="testDate", type="date")
     */
    private $testDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isBug", type="boolean")
     */
    private $isBug;


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
     * Set priority
     *
     * @param integer $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
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
     * Set testDate
     *
     * @param \DateTime $testDate
     * @return Task
     */
    public function setTestDate($testDate)
    {
        $this->testDate = $testDate;
    
        return $this;
    }

    /**
     * Get testDate
     *
     * @return \DateTime 
     */
    public function getTestDate()
    {
        return $this->testDate;
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
}
