<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\Project;
use CoralScrum\MainBundle\Form\ProjectType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Project controller.
 *
 */
class ProjectController extends Controller
{

    /**
     * Lists all Project entities.
     *
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('CoralScrumMainBundle:Project')->getVisibleProject($user);

        return $this->render('CoralScrumMainBundle:Project:index.html.twig', array(
            'entities' => $projects,
        ));
    }
    /**
     * Creates a new Project entity.
     *
     */
    public function createAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $project = new Project();
        $project->setOwner($user);
        $form = $this->createCreateForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('project_show', array(
                'projectId' => $project->getId())
            ));
        }

        return $this->render('CoralScrumMainBundle:Project:new.html.twig', array(
            'entity' => $project,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Project entity.
    *
    * @param Project $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity, array(
            'action' => $this->generateUrl('project_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Project entity.
     *
     */
    public function newAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $project = new Project();

        $form   = $this->createCreateForm($project);

        return $this->render('CoralScrumMainBundle:Project:new.html.twig', array(
            'entity' => $project,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Project entity.
     *
     */
    public function showAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId);

        return $this->render('CoralScrumMainBundle:Project:show.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Finds and displays a Project entity.
     *
     */
    public function settingsAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId);

        return $this->render('CoralScrumMainBundle:Project:settings.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     */
    public function editAction($projectId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        if ($user != $project->getOwner()) {
            throw new AccessDeniedException('You are not the owner of this project.');
        }

        $editForm = $this->createEditForm($project);
        $deleteForm = $this->createDeleteForm($projectId);

        return $this->render('CoralScrumMainBundle:Project:edit.html.twig', array(
            'entity'      => $project,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Project entity.
    *
    * @param Project $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity, array(
            'action' => $this->generateUrl('project_update', array('projectId' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Project entity.
     *
     */
    public function updateAction(Request $request, $projectId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('project_edit', array('projectId' => $projectId)));
        }

        return $this->render('CoralScrumMainBundle:Project:edit.html.twig', array(
            'entity'      => $entity,
            'projectId' => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Project entity.
     *
     */
    public function deleteAction(Request $request, $projectId)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $form = $this->createDeleteForm($projectId);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

            if (!$project) {
                throw $this->createNotFoundException('Unable to find Project entity.');
            }

            if ($user != $project->getOwner()) {
                throw new AccessDeniedException('You are not the owner of this project.');
            }

            $em->remove($project);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('project'));
    }

    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('projectId' => $projectId)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
