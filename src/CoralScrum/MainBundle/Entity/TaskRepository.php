<?php

namespace CoralScrum\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends EntityRepository
{
    public function countBySprintId($sprintId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(us.id)')
           ->join('t.userStory', 'us')
           ->join('us.sprint', 'sp')
           ->where('sp.id = :sprintId')
           ->setParameter('sprintId', $sprintId)
           ;
        return $qb->getQuery()->getSingleScalarResult();
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

    public function findTaskToCloneBySprintId($sprintId)
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->where('ta.sprint = :sprintId')
           ->andWhere('ta.state IN (:state)')
           ->setParameters(array(
               'sprintId' => $sprintId,
               'state'    => array(0, 1),
            ))
           ;
        return $qb->getQuery()->getResult();
    }

    public function countTaskDoneByTaskId($taskId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t2.id)')
           ->join('t.userStory', 'us')
           ->join('us.task', 't2')
           ->where('t.id = :taskId')
           ->andWhere('t2.state = :state')
           ->setParameters(array(
               'taskId' => $taskId,
               'state'  => 2,
            ))
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countTaskNotDoneByTaskId($taskId, $sprintId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t2.id)')
           ->join('t.userStory', 'us')
           ->join('us.task', 't2')
           ->where('t.id = :taskId')
           ->andWhere('t2.sprint = :sprintId')
           ->andWhere('t2.state IN (:state)')
           ->setParameters(array(
               'taskId'   => $taskId,
               'sprintId' => $sprintId,
               'state'    => array(0, 1),
            ))
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countTaskNotDoneBySprintId($sprintId)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('count(t.id)')
           ->join('t.userStory', 'us')
           ->join('us.sprint', 'sp')
           ->where('sp.id = :sprintId')
           ->andWhere('t.state IN (:state)')
           ->setParameters(array(
               'sprintId' => $sprintId,
               'state'    => array(0, 1),
            ))
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findOneByIdJoined($taskId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, u, t2 FROM CoralScrumMainBundle:Task t
                LEFT JOIN t.user u
                LEFT JOIN t.dependency t2
                WHERE t.id = :taskId'
            )->setParameter('taskId', $taskId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByIdJoined($sprintId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT t, u, t2, us FROM CoralScrumMainBundle:Task t
                LEFT JOIN t.user u
                LEFT JOIN t.dependency t2
                JOIN t.userStory us
                JOIN us.sprint sp
                WHERE sp.id = :sprintId'
            )->setParameter('sprintId', $sprintId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function getMaxTaskId($projectId)
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select('max(ta.displayId)')
           ->join('ta.userStory', 'us')
           ->where('us.project = :projectId')
           ->setParameter('projectId', $projectId)
           ;
        return $qb->getQuery()->getSingleScalarResult();
    }
}
