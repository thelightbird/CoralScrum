<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\Sprint;
use CoralScrum\MainBundle\Form\SprintType;
use CoralScrum\MainBundle\Services\Security;

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
            $em = $this->getDoctrine()->getManager();
            $em->persist($sprint);
            $em->flush();

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
        
        $em = $this->getDoctrine()->getManager();
        $tests = $em->getRepository('CoralScrumMainBundle:Test')->findBySprintId($sprintId);
        $sprint = $em->getRepository('CoralScrumMainBundle:Sprint')->findOneByIdJoined($sprintId);

        if (!$sprint) {
            throw $this->createNotFoundException('Unable to find Sprint entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $sprintId);

        return $this->render('CoralScrumMainBundle:Sprint:show.html.twig', array(
            'tests'       => $tests,
            'entity'      => $sprint,
            'sprintId'    => $sprintId,
            'isGranted'   => $isGranted,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),
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
        $form = $this->createForm(new SprintType(), $entity, array(
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
