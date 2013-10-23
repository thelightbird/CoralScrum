<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\UserStory;
use CoralScrum\MainBundle\Form\UserStoryType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoralScrumMainBundle:UserStory')->findAll();

        return $this->render('CoralScrumMainBundle:UserStory:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new UserStory entity.
     *
     */
    public function createAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $projectId = 1; // TODO change route to get project ID
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        $userstory = new UserStory();
        $userstory->setProject($project);
        $form = $this->createCreateForm($userstory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userstory);
            $em->flush();

            return $this->redirect($this->generateUrl('userstory_show', array(
                'id' => $userstory->getId())
            ));
        }

        return $this->render('CoralScrumMainBundle:UserStory:new.html.twig', array(
            'entity' => $userstory,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a UserStory entity.
    *
    * @param UserStory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(UserStory $entity)
    {
        $form = $this->createForm(new UserStoryType(), $entity, array(
            'action' => $this->generateUrl('userstory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserStory entity.
     *
     */
    public function newAction()
    {
        $entity = new UserStory();
        $form   = $this->createCreateForm($entity);

        return $this->render('CoralScrumMainBundle:UserStory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserStory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoralScrumMainBundle:UserStory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing UserStory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoralScrumMainBundle:UserStory:edit.html.twig', array(
            'entity'      => $entity,
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
    private function createEditForm(UserStory $entity)
    {
        $form = $this->createForm(new UserStoryType(), $entity, array(
            'action' => $this->generateUrl('userstory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserStory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserStory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('userstory_edit', array('id' => $id)));
        }

        return $this->render('CoralScrumMainBundle:UserStory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UserStory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoralScrumMainBundle:UserStory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserStory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('userstory'));
    }

    /**
     * Creates a form to delete a UserStory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userstory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
