<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CoralScrum\MainBundle\Entity\UserStory;
use CoralScrum\MainBundle\Form\UserStoryType;
use CoralScrum\MainBundle\Services\Security;

/**
 * UserStory controller.
 *
 */
class UserStoryController extends Controller
{

    /**
     * Lists all UserStory entities.
     *
     */
    public function indexAction($projectId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoralScrumMainBundle:UserStory')->findByProject($projectId);

        return $this->render('CoralScrumMainBundle:UserStory:index.html.twig', array(
            'entities'  => $entities,
            'isGranted' => $isGranted,
            'projectId' => $projectId,
        ));
    }
    /**
     * Creates a new UserStory entity.
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
        
        $userstory = new UserStory();
        $userstory->setProject($project);
        $userstory->setIsValidated(true);
        $form = $this->createCreateForm($projectId, $userstory);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userstory);
            $em->flush();

            return $this->redirect($this->generateUrl('userstory_show', array(
                'id'        => $userstory->getId(),
                'projectId' => $projectId,
            )));
        }
        return $this->render('CoralScrumMainBundle:UserStory:new.html.twig', array(
            'entity'    => $userstory,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a UserStory entity.
    *
    * @param UserStory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($projectId, UserStory $entity)
    {
        $form = $this->createForm(new UserStoryType(), $entity, array(
            'action' => $this->generateUrl('userstory_create', array(
                'projectId' => $projectId,
            )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserStory entity.
     *
     */
    public function newAction($projectId)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $entity = new UserStory();
        $form   = $this->createCreateForm($projectId, $entity);

        return $this->render('CoralScrumMainBundle:UserStory:new.html.twig', array(
            'entity'    => $entity,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserStory entity.
     *
     */
    public function showAction($projectId, $id)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $id);

        return $this->render('CoralScrumMainBundle:UserStory:show.html.twig', array(
            'entity'      => $entity,
            'isGranted'   => $isGranted,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserStory entity.
     *
     */
    public function editAction($projectId, $id)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $editForm = $this->createEditForm($projectId, $entity);
        $deleteForm = $this->createDeleteForm($projectId, $id);

        return $this->render('CoralScrumMainBundle:UserStory:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a UserStory entity.
    *
    * @param UserStory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($projectId, UserStory $entity)
    {
        $form = $this->createForm(new UserStoryType(), $entity, array(
            'action' => $this->generateUrl('userstory_update', array(
                'id'        => $entity->getId(),
                'projectId' => $projectId,
            )),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserStory entity.
     *
     */
    public function updateAction($projectId, Request $request, $id)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $id);
        $editForm = $this->createEditForm($projectId, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('userstory', array(
                    'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:UserStory:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UserStory entity.
     *
     */
    public function deleteAction($projectId, Request $request, $id)
    {
        $isGranted = $this->get('csm_security')->isGranted($projectId);
        if (!$isGranted) {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('userstory', array(
                'projectId' => $projectId,
        )));
    }

    /**
     * Creates a form to delete a UserStory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userstory_delete', array(
                'id' => $id,
                'projectId' => $projectId,
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
