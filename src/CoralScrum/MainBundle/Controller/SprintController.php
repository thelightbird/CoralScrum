<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CoralScrum\MainBundle\Entity\Sprint;
use CoralScrum\MainBundle\Form\SprintType;
use CoralScrum\MainBundle\Form\SprintEditType;
use CoralScrum\MainBundle\Services\Security;

use Symfony\Component\HttpFoundation\Response;
/**
 * Sprint controller.
 *
 */
class SprintController extends Controller
{

    /**
     * Lists all Sprint entities.
     *
     */
    public function indexAction($projectId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        
        $em = $this->getDoctrine()->getManager();

        $sprints = $em->getRepository('CoralScrumMainBundle:Sprint')->findByIdJoinedToUserStory($projectId);

        return $this->render('CoralScrumMainBundle:Sprint:index.html.twig', array(
            'entities'  => $sprints,
            'isGranted' => $isGranted,
            'projectId' => $projectId,
        ));
    }
    /**
     * Creates a new Sprint entity.
     *
     */
    public function createAction($projectId, Request $request)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        $sprint = new Sprint();
        $sprint->setProject($project);
        $form = $this->createCreateForm($projectId, $sprint);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $sprintId = $em->getRepository('CoralScrumMainBundle:Sprint')->getMaxSprintId($projectId);

            $sprintDisplayId = $em->getRepository('CoralScrumMainBundle:Sprint')->getMaxSprintDisplayId($projectId);
            $sprintDisplayId = is_null($sprintDisplayId) ? 1 : $sprintDisplayId + 1;
            $sprint->setDisplayId($sprintDisplayId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sprint);
            $em->flush();

            // Clone tasks undone of previous Sprint
            $tasks = $em->getRepository('CoralScrumMainBundle:Task')->findTaskToCloneBySprintId($sprintId);

            foreach ($tasks as $task) {
                $cloneTask = clone $task;
                $cloneTask->setSprint($sprint);
                $em->persist($cloneTask);
            }
            $em->flush();
            // -------------------------------------

            return $this->redirect($this->generateUrl('sprint_show', array(
                'projectId' => $projectId,
                'sprintId'  => $sprint->getId(),
            )));
        }

        return $this->render('CoralScrumMainBundle:Sprint:new.html.twig', array(
            'entity'    => $sprint,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Sprint entity.
    *
    * @param Sprint $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($projectId, Sprint $entity)
    {
        $form = $this->createForm(new SprintType(), $entity, array(
            'action'    => $this->generateUrl('sprint_create', array(
                'projectId' => $projectId,
            )),
            'method'    => 'POST',
            'projectId' => $projectId,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sprint entity.
     *
     */
    public function newAction($projectId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $entity = new Sprint();
        
        $form   = $this->createCreateForm($projectId, $entity);

        return $this->render('CoralScrumMainBundle:Sprint:new.html.twig', array(
            'entity'    => $entity,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sprint entity.
     *
     */
    public function showAction($projectId, $sprintId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        $isSprintFinished = $this->get('csm_security')->isSprintFinished($sprintId);
        
        $em = $this->getDoctrine()->getManager();
        $tests = $em->getRepository('CoralScrumMainBundle:Test')->findBySprintId($sprintId);
        $sprint = $em->getRepository('CoralScrumMainBundle:Sprint')->findOneByIdJoined($sprintId);

        if (!$sprint) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $sprintId);

        return $this->render('CoralScrumMainBundle:Sprint:show.html.twig', array(
            'tests'            => $tests,
            'entity'           => $sprint,
            'sprintId'         => $sprintId,
            'isGranted'        => $isGranted,
            'projectId'        => $projectId,
            'isSprintFinished' => $isSprintFinished,
            'delete_form'      => $deleteForm->createView(),
        ));
    }

    /**
     * Displays stats (Burn Down Chart) for this Sprint
     *
     */
    public function statsAction($projectId, $sprintId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        $isSprintFinished = $this->get('csm_security')->isSprintFinished($sprintId);

        $em = $this->getDoctrine()->getManager();
        $sprint = $em->getRepository('CoralScrumMainBundle:Sprint')->find($sprintId);
        $userContributionsTaskDone = $em->getRepository('CoralScrumMainBundle:Sprint')->getUserContributionsTaskDoneBySprintId($sprintId);
        $userContributionsTaskInProgress = $em->getRepository('CoralScrumMainBundle:Sprint')->getUserContributionsTaskInProgressBySprintId($sprintId);

        // Burndown Chart Task Estimates
        $conn = $this->get('database_connection');
        $sprintId = intval($sprintId);
        $sql = '
            SELECT SUM(ta.duration) AS sum_duration, ta.endDate AS end_date
            FROM sprint_userstories sp_us
            JOIN Task ta ON sp_us.userstory_id = ta.userstory_id
            WHERE sp_us.sprint_id = '.$sprintId.'
            AND ta.sprint_id = '.$sprintId.'
            GROUP BY YEAR(ta.endDate), MONTH(ta.endDate), DAY(ta.endDate)
            ORDER BY ta.endDate
        ';
        $resBurnDownData = $conn->fetchAll($sql);


        // get TotalDuration
        $burndownTotalDuration = 0;
        foreach ($resBurnDownData as $res) {
            $burndownTotalDuration += $res['sum_duration'];
        }
        if ($burndownTotalDuration == 0) {
            $burndownTotalDuration = 7;
        }

        // get BurnDownChart Data
        $burndownData = array();
        $dayValue = $burndownTotalDuration;
        $now = new \DateTime("now");
        $date = clone $sprint->getStartDate();
        $sprintDuration = $sprint->getDuration();
        for ($k=0; $k<=$sprintDuration; $k++) {
            if ($date > $now) {
                break;
            }
            $burndownData[$k] = $dayValue;
            foreach ($resBurnDownData as $val) {
                $time = strtotime($val['end_date']);
                $sqlDate = date('Y-m-d', $time);
                
                if ($sqlDate == $date->format('Y-m-d')) {
                    $burndownData[$k] = $dayValue - $val['sum_duration'];
                    $dayValue = $burndownData[$k];
                    break;
                }
            }
            $date->modify('+1 days');
        }

        if (!$sprint) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        return $this->render('CoralScrumMainBundle:Sprint:stats.html.twig', array(
            'entity'                => $sprint,
            'sprintId'              => $sprintId,
            'isGranted'             => $isGranted,
            'projectId'             => $projectId,
            'burndownData'          => $burndownData,
            'isSprintFinished'      => $isSprintFinished,
            'burndownTotalDuration' => $burndownTotalDuration,
            'userContributionsTaskDone'       => $userContributionsTaskDone,
            'userContributionsTaskInProgress' => $userContributionsTaskInProgress,
        ));
    }

    /**
     * Displays a form to edit an existing Sprint entity.
     *
     */
    public function editAction($projectId, $sprintId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        $isSprintFinished = $this->get('csm_security')->isSprintFinished($sprintId);
        if ($isSprintFinished) {
            throw new AccessDeniedException('This sprint is finished, no changes are allowed.');
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Sprint')->find($sprintId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        $editForm = $this->createEditForm($projectId, $entity);
        $deleteForm = $this->createDeleteForm($projectId, $sprintId);

        return $this->render('CoralScrumMainBundle:Sprint:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Sprint entity.
    *
    * @param Sprint $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($projectId, Sprint $entity)
    {
        $form = $this->createForm(new SprintEditType(), $entity, array(
            'action' => $this->generateUrl('sprint_update', array(
                'projectId' => $projectId,
                'sprintId'  => $entity->getId()
            )),
            'method' => 'PUT',
            'projectId' => $projectId,
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Sprint entity.
     *
     */
    public function updateAction($projectId, Request $request, $sprintId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Sprint')->find($sprintId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $sprintId);
        $editForm = $this->createEditForm($projectId, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sprint', array(
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:Sprint:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Sprint entity.
     *
     */
    public function deleteAction($projectId, Request $request, $sprintId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoralScrumMainBundle:Sprint')->find($sprintId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('sprint', array(
            'projectId' => $projectId,
        )));
    }

    /**
     * Creates a form to delete a Sprint entity by id.
     *
     * @param mixed $sprintId The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId, $sprintId)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sprint_delete', array(
                'sprintId'  => $sprintId,
                'projectId' => $projectId,
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
