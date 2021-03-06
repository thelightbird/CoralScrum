<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SprintRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SprintRepository extends EntityRepository
{
    public function findOneByIdJoined($sprintId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT sp, us, ta, u FROM CoralScrumMainBundle:Sprint sp
                LEFT JOIN sp.userStory us
                LEFT JOIN us.task ta WITH ta.sprint = :sprintId
                LEFT JOIN ta.user u
                WHERE sp.id = :sprintId'
            )->setParameter('sprintId', $sprintId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByIdJoinedToUserStory($projectId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT sp, us FROM CoralScrumMainBundle:Sprint sp
                JOIN sp.project p
                LEFT JOIN sp.userStory us
                WHERE p.id = :projectId'
            )->setParameter('projectId', $projectId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function getMaxSprintDisplayId($projectId)
    {
        $qb = $this->createQueryBuilder('sp');
        $qb->select('max(sp.displayId)')
           ->where('sp.project = :projectId')
           ->setParameter('projectId', $projectId)
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getMaxSprintId($projectId)
    {
        $qb = $this->createQueryBuilder('sp');
        $qb->select('max(sp.id)')
           ->where('sp.project = :projectId')
           ->setParameter('projectId', $projectId)
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getUserContributionsTaskDoneBySprintId($sprintId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT
                    SUM(ta.duration) AS totalDuration,
                    u.username, u.firstname, u.lastname
                FROM CoralScrumMainBundle:Sprint sp
                JOIN sp.userStory us
                JOIN us.task ta WITH ta.sprint = :sprintId
                JOIN ta.user u
                WHERE sp.id = :sprintId
                AND ta.state = 2
                GROUP BY u.id
                ORDER BY totalDuration DESC'
            )->setParameter('sprintId', $sprintId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function getUserContributionsTaskInProgressBySprintId($sprintId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT
                    SUM(ta.duration) AS totalDuration,
                    u.username, u.firstname, u.lastname
                FROM CoralScrumMainBundle:Sprint sp
                JOIN sp.userStory us
                JOIN us.task ta WITH ta.sprint = :sprintId
                JOIN ta.user u
                WHERE sp.id = :sprintId
                AND ta.state = 1
                GROUP BY u.id
                ORDER BY totalDuration DESC'
            )->setParameter('sprintId', $sprintId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
