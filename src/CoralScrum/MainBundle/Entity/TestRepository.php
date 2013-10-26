<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TestRepository extends EntityRepository
{
    public function findByProjectId($projectId)
    {
        $em = $this->getEntityManager();
        $query = $em
                ->createQuery("
                    SELECT t
                    FROM CoralScrum\MainBundle\Entity\Test t
                    JOIN t.userStory us
                    JOIN us.project p
                    WHERE p.id = :projectId
                ")
                ->setParameter('projectId', $projectId);

        return $query->getResult();
    }

    public function findBySprintId($sprintId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->join('t.userStory', 'us')
           ->join('us.sprint', 'sp')
           ->where('sp.id = :sprintId')
           ->setParameter('sprintId', $sprintId)
           ;

        return $qb->getQuery()->getResult();
    }
}
