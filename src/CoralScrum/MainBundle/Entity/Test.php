<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoralScrum\MainBundle\Entity\TestRepository")
 */
class Test
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
     * @ORM\ManyToOne(targetEntity="CoralScrum\MainBundle\Entity\UserStory", inversedBy="test")
     **/
    private $userStory;

    /**
     * @ORM\ManyToOne(targetEntity="CoralScrum\UserBundle\Entity\User")
     */
    private $tester;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="input", type="text", nullable=true)
     */
    private $input;

    /**
     * @var string
     *
     * @ORM\Column(name="testCase", type="text")
     */
    private $testCase;

    /**
     * @var string
     *
     * @ORM\Column(name="expectedResult", type="text")
     */
    private $expectedResult;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="testDate", type="datetime", nullable=true)
     */
    private $testDate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * 0 : not tested
     * 1 : test passed
     * 2 : test failed
     *
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;


    public function __toString()
    {
        return "#".$this->displayId.": ".$this->title;
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
     * @return Test
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
     * Set title
     *
     * @param string $title
     * @return Test
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
     * Set input
     *
     * @param string $input
     * @return Test
     */
    public function setInput($input)
    {
        $this->input = $input;
    
        return $this;
    }

    /**
     * Get input
     *
     * @return string 
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set testCase
     *
     * @param string $testCase
     * @return Test
     */
    public function setTestCase($testCase)
    {
        $this->testCase = $testCase;
    
        return $this;
    }

    /**
     * Get testCase
     *
     * @return string 
     */
    public function getTestCase()
    {
        return $this->testCase;
    }

    /**
     * Set expectedResult
     *
     * @param string $expectedResult
     * @return Test
     */
    public function setExpectedResult($expectedResult)
    {
        $this->expectedResult = $expectedResult;
    
        return $this;
    }

    /**
     * Get expectedResult
     *
     * @return string 
     */
    public function getExpectedResult()
    {
        return $this->expectedResult;
    }

    /**
     * Set testDate
     *
     * @param \DateTime $testDate
     * @return Test
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
     * Set comment
     *
     * @param string $comment
     * @return Test
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Test
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->state = 0;
    }

    /**
     * Set userStory
     *
     * @param \CoralScrum\MainBundle\Entity\UserStory $userStory
     * @return Test
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
     * Set tester
     *
     * @param \CoralScrum\UserBundle\Entity\User $tester
     * @return Test
     */
    public function setTester(\CoralScrum\UserBundle\Entity\User $tester = null)
    {
        $this->tester = $tester;
    
        return $this;
    }

    /**
     * Get tester
     *
     * @return \CoralScrum\UserBundle\Entity\User 
     */
    public function getTester()
    {
        return $this->tester;
    }
}