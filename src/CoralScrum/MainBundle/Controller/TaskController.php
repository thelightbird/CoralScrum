<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\Task;
use CoralScrum\MainBundle\Form\TaskType;
use CoralScrum\MainBundle\Form\TaskEditType;
/**
 * Task controller.
 *
 */
class TaskController extends Controller
{
    /**
     * Get $projectId from $sprintId
     *
     */
    public function getProjectId($sprintId)
    {
        $em = $this->getDoctrine()->getManager();

        $sprint = $em->getRepository('CoralScrumMainBundle:Sprint')->find($sprintId);
        if (!$sprint) {
            throw $this->createNotFoundException('Bad Sprint id.');
        }
        $projectId = $sprint->getProject()->getId();

        return $projectId;
    }

    /**
     * Lists all Task entities.
     *
     */
    public function indexAction($sprintId)
    {
        $projectId = $this->getProjectId($sprintId);

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoralScrumMainBundle:Task')->findBySprintId($sprintId);

        return $this->render('CoralScrumMainBundle:Task:index.html.twig', array(
            'entities'  => $entities,
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
        ));
    }
    /**
     * Creates a new Task entity.
     *
     */
    public function createAction($sprintId, Request $request)
    {
        $projectId = $this->getProjectId($sprintId);

        $task = new Task();
        $form = $this->createCreateForm($projectId, $sprintId, $task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->getUserStory()->setIsFinished(false);
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('sprint_show', array(
                'sprintId'  => $sprintId,
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:Task:new.html.twig', array(
            'entity'    => $task,
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($projectId, $sprintId, Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_create', array(
                'sprintId'  => $sprintId,
                'projectId' => $projectId,
            )),
            'method' => 'POST',
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     */
    public function newAction($sprintId)
    {
        $projectId = $this->getProjectId($sprintId);

        $em = $this->getDoctrine()->getManager();
        $nbUserStories = $em->getRepository('CoralScrumMainBundle:UserStory')->countBySprintId($sprintId);

        if ($nbUserStories == 0) {
            throw $this->createNotFoundException('Before adding a Task, you need to add a new User Story to this Sprint.');
        }

        $entity = new Task();
        $form   = $this->createCreateForm($projectId, $sprintId, $entity);

        return $this->render('CoralScrumMainBundle:Task:new.html.twig', array(
            'entity'    => $entity,
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($sprintId, $id)
    {
        $projectId = $this->getProjectId($sprintId);

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $sprintId, $id);

        return $this->render('CoralScrumMainBundle:Task:show.html.twig', array(
            'entity'      => $entity,
            'sprintId'    => $sprintId,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($sprintId, $id)
    {
        $projectId = $this->getProjectId($sprintId);

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($projectId, $sprintId, $entity);
        $deleteForm = $this->createDeleteForm($projectId, $sprintId, $id);

        return $this->render('CoralScrumMainBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'sprintId'    => $sprintId,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function editstateAction($sprintId)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) { // Ajax request
            $taskId = $request->request->get('taskId');

            $user = $this->container->get('security.context')->getToken()->getUser();
            if (!is_object($user)) {
                throw new AccessDeniedException('You are not logged in.');
            }

            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('CoralScrumUserBundle:User')->findByTaskId($taskId);

            if (in_array($user, $users)) {

                $task = $em->getRepository('CoralScrumMainBundle:Task')->find($taskId);
                if (!$task) {
                    throw $this->createNotFoundException('Unable to find Task entity.');
                }

                $needsUpdate = false;
                $taskState = $request->request->get('taskState');
                if ($taskState == "ToDo" && $task->getState() != 0) {
                    $task->setState(0);
                    $needsUpdate = true;
                }
                else if ($taskState == "InProgress" && $task->getState() != 1) {
                    $task->setState(1);
                    if (is_null($task->getStartDate())) {
                        $task->setStartDate(new \DateTime());
                    }
                    $needsUpdate = true;
                }
                else if ($taskState == "Done" && $task->getState() != 2) {
                    $task->setState(2);
                    if (is_null($task->getStartDate())) {
                        $task->setStartDate(new \DateTime());
                    }
                    $task->setEndDate(new \DateTime());
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $em->flush();

                    // Update User Story isFinished
                    if ($taskState == "Done") {
                        $nbTaskNotDone = $em->getRepository('CoralScrumMainBundle:Task')->countTaskNotDoneByTaskId($taskId);
                        if ($nbTaskNotDone == 0) {
                            $task->getUserStory()->setIsFinished(true);
                            $em->flush();
                        }
                    }
                    else if ($taskState == "ToDo" || $taskState == "InProgress" ) {
                        $task->getUserStory()->setIsFinished(false);
                        $em->flush();
                    }
                }

                return new Response("Ok");
            }
            throw new AccessDeniedException('You are not authorized to change this task state.');
        }
        return new Response("");
    }

    /**
    * Creates a form to edit a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($projectId, $sprintId, Task $entity)
    {
        $form = $this->createForm(new TaskEditType(), $entity, array(
            'action' => $this->generateUrl('task_update', array(
                'id'        => $entity->getId(),
                'projectId' => $projectId,
                'sprintId'  => $sprintId,
            )),
            'method' => 'PUT',
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
            'taskId'    => $entity->getId(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction($sprintId, Request $request, $id)
    {
        $projectId = $this->getProjectId($sprintId);

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $sprintId, $id);
        $editForm = $this->createEditForm($projectId, $sprintId, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sprint_show', array(
                'sprintId'  => $sprintId,
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'sprintId'    => $sprintId,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction($sprintId, Request $request, $id)
    {
        $projectId = $this->getProjectId($sprintId);
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('CoralScrumMainBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $taskId = $task->getId();
        $taskUserStory = $task->getUserStory();

        $em->remove($task);
        $em->flush();

        // Update UserStory isFinished
        $nbTaskNotDone = $em->getRepository('CoralScrumMainBundle:Task')->countTaskNotDoneByTaskId($taskId);
        if ($nbTaskNotDone == 0) {
            $taskUserStory->setIsFinished(true);
        }
        else {
            $taskUserStory->setIsFinished(false);
        }
        $em->flush();

        return $this->redirect($this->generateUrl('task', array(
            'sprintId'  => $sprintId,
            'projectId' => $projectId,
        )));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId, $sprintId, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array(
                'id'        => $id,
                'sprintId'  => $sprintId,
                'projectId' => $projectId,
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
