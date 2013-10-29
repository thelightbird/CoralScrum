<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\UserProject;
use CoralScrum\MainBundle\Form\UserProjectType;

/**
 * UserProject controller.
 *
 */
class UserProjectController extends Controller
{

    /**
     * Lists all UserProject entities.
     *
     */
    public function indexAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoralScrumMainBundle:UserProject')->findByIdJoined($projectId);

        return $this->render('CoralScrumMainBundle:UserProject:index.html.twig', array(
            'entities'  => $entities,
            'projectId' => $projectId,
        ));
    }
    /**
     * Creates a new UserProject entity.
     *
     */
    public function createAction($projectId, Request $request)
    {
        $entity = new UserProject();
        $form = $this->createCreateForm($projectId, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('collaborator', array(
                'id'        => $entity->getId(),
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:UserProject:new.html.twig', array(
            'entity'    => $entity,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a UserProject entity.
    *
    * @param UserProject $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($projectId, UserProject $entity)
    {
        $form = $this->createForm(new UserProjectType(), $entity, array(
            'action' => $this->generateUrl('collaborator_create', array(
                'projectId' => $projectId,
            )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserProject entity.
     *
     */
    public function newAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $userProject = new UserProject();
        $userProject->setProject($project);
        $form   = $this->createCreateForm($projectId, $userProject);

        return $this->render('CoralScrumMainBundle:UserProject:new.html.twig', array(
            'entity'    => $userProject,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserProject entity.
     *
     */
    public function editAction($projectId, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserProject entity.');
        }

        $editForm = $this->createEditForm($projectId, $entity);
        $deleteForm = $this->createDeleteForm($projectId, $id);

        return $this->render('CoralScrumMainBundle:UserProject:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a UserProject entity.
    *
    * @param UserProject $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($projectId, UserProject $entity)
    {
        $form = $this->createForm(new UserProjectType(), $entity, array(
            'action' => $this->generateUrl('collaborator_update', array(
                'id'        => $entity->getId(),
                'projectId' => $projectId,
            )),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserProject entity.
     *
     */
    public function updateAction($projectId, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserProject entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $id);
        $editForm = $this->createEditForm($projectId, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('collaborator', array(
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:UserProject:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UserProject entity.
     *
     */
    public function deleteAction($projectId, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoralScrumMainBundle:UserProject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserProject entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('collaborator', array(
            'projectId' => $projectId,
        )));
    }

    /**
     * Creates a form to delete a UserProject entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('collaborator_delete', array(
                'id'        => $id,
                'projectId' => $projectId,
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
