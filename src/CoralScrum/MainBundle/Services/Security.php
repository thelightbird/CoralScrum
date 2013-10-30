<?php

namespace CoralScrum\MainBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Security {

    /**
     *
     * @var EntityManager 
     */
    protected $em;

    /**
     *
     * @var SecurityContextInterface 
     */
    protected $securityContext;

    public function __construct(EntityManager $entityManager, SecurityContextInterface $securityContext)
    {
        $this->em = $entityManager;
        $this->securityContext = $securityContext;
    }

    /**
     * Check if user belongs to the project.
     *
     */
	public function checkUserMembership($projectId)
    {
        $user = $this->securityContext->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $users = $this->em->getRepository('CoralScrumUserBundle:User')->findByProjectId($projectId);
        if (!in_array($user, $users)) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        return $user;
	}

    /**
     * Check if user is the creator of the project.
     *
     */
    public function isCreator($projectId)
    {
        $user = $this->securityContext->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $isCreator = $this->em->getRepository('CoralScrumMainBundle:Project')->isCreator($projectId, $user);
        if (!$isCreator) {
            throw $this->createNotFoundException('Only the project creator can access this page.');
        }

        return $user;
    }
}