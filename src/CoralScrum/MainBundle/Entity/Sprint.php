<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sprint
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="sprintId_uc", columns={"project_id", "displayId"})})
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\SprintRepository")
 */
class Sprint
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
     * @var \Integer
     *
     * @ORM\Column(name="displayId", type="integer")
     */
    private $displayId;

    /**
     * @var Project $project
     *
     * @ORM\ManyToOne(targetEntity="CoralScrum\MainBundle\Entity\Project", inversedBy="sprint")
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="CoralScrum\MainBundle\Entity\UserStory", inversedBy="sprint")
     * @ORM\JoinTable(name="sprint_userstories",
     *      joinColumns={@ORM\JoinColumn(name="sprint_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="userStory_id", referencedColumnName="id")}
     *      )
     **/
    private $userStory;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;


    public function __toString()
    {
        return "#".$this->displayId;
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
     * Set displayId
     *
     * @param integer $displayId
     * @return Sprint
     */
    public function setDisplayId($displayId)
    {
        $this->displayId = $displayId;
    
        return $this;
    }

    /**
     * Get displayId
     *
     * @return integer 
     */
    public function getDisplayId()
    {
        return $this->displayId;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Sprint
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
     * Set duration
     *
     * @param integer $duration
     * @return Sprint
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
     * Constructor
     */
    public function __construct()
    {
        $this->userStory = new \Doctrine\Common\Collections\ArrayCollection();
        $this->startDate = new \DateTime();
    }

    /**
     * Set project
     *
     * @param \CoralScrum\MainBundle\Entity\Project $project
     * @return Sprint
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
     * Add userStory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userStory
     * @return Sprint
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
}