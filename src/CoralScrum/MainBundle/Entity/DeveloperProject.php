<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeveloperProject
 *
 * @ORM\Table(name="developer_project")
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\DeveloperProjectRepository")
 */
class DeveloperProject
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="developerproject")
     */
    private $project;

    /**
     * @var Developer $developer
     *
     * @ORM\ManyToOne(targetEntity="Developer", inversedBy="developerproject")
     */
    private $developer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAccept", type="boolean")
     */
    private $isAccept;

    /**
     * Set isAccept
     *
     * @param boolean $isAccept
     * @return Task
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
