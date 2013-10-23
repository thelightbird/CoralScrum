<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sprint
 *
 * @ORM\Table()
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
     * @ORM\ManyToMany(targetEntity="CoralScrum\MainBundle\Entity\UserStory")
     * @ORM\JoinTable(name="sprint_userstories",
     *      joinColumns={@ORM\JoinColumn(name="sprint_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="userstory_id", referencedColumnName="id")}
     *      )
     **/
    private $userstory;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;



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
        $this->userstory = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add userstory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userstory
     * @return Sprint
     */
    public function addUserstory(\CoralScrum\MainBundle\Entity\UserStory $userstory)
    {
        $this->userstory[] = $userstory;
    
        return $this;
    }

    /**
     * Remove userstory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userstory
     */
    public function removeUserstory(\CoralScrum\MainBundle\Entity\UserStory $userstory)
    {
        $this->userstory->removeElement($userstory);
    }

    /**
     * Get userstory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserstory()
    {
        return $this->userstory;
    }
}