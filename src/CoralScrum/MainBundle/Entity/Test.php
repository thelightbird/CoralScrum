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
     * @ORM\ManyToOne(targetEntity="CoralScrum\MainBundle\Entity\UserStory")
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
     * @ORM\Column(name="input", type="text")
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Test
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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