<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\MainBundle\Entity\Test;
use CoralScrum\MainBundle\Form\TestType;

/**
 * Test controller.
 *
 */
class TestController extends Controller
{

    /**
     * Lists all Test entities.
     *
     */
    public function indexAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoralScrumMainBundle:Test')->findByProjectId($projectId);

        return $this->render('CoralScrumMainBundle:Test:index.html.twig', array(
            'entities' => $entities,
            'projectId' => $projectId,
        ));
    }
    /**
     * Creates a new Test entity.
     *
     */
    public function createAction($projectId, Request $request)
    {
        $entity = new Test();
        $form = $this->createCreateForm($projectId, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('test_show', array(
                'id'        => $entity->getId(),
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:Test:new.html.twig', array(
            'entity'    => $entity,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Test entity.
    *
    * @param Test $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($projectId, Test $entity)
    {
        $form = $this->createForm(new TestType(), $entity, array(
            'action'    => $this->generateUrl('test_create', array(
                'projectId' => $projectId,
            )),
            'method'    => 'POST',
            'projectId' => $projectId,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Test entity.
     *
     */
    public function newAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();
        $nbUserStories = $em->getRepository('CoralScrumMainBundle:UserStory')->countByProjectId($projectId);
        
        if ($nbUserStories == 0) {
            throw $this->createNotFoundException('Before adding a Test, you need to add a new User Story.');
        }

        $entity = new Test();
        $entity->setDate(new \DateTime());
        $form   = $this->createCreateForm($projectId, $entity);

        return $this->render('CoralScrumMainBundle:Test:new.html.twig', array(
            'entity'    => $entity,
            'projectId' => $projectId,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Test entity.
     *
     */
    public function showAction($projectId, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Test')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Test entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $id);

        return $this->render('CoralScrumMainBundle:Test:show.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Test entity.
     *
     */
    public function editAction($projectId, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Test')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Test entity.');
        }

        $editForm = $this->createEditForm($projectId, $entity);
        $deleteForm = $this->createDeleteForm($projectId, $id);

        return $this->render('CoralScrumMainBundle:Test:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Test entity.
    *
    * @param Test $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($projectId, Test $entity)
    {
        $form = $this->createForm(new TestType(), $entity, array(
            'action'    => $this->generateUrl('test_update', array(
                'projectId' => $projectId,
                'id'        => $entity->getId()
            )),
            'method'    => 'PUT',
            'projectId' => $projectId,
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Test entity.
     *
     */
    public function updateAction($projectId, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoralScrumMainBundle:Test')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Test entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId, $id);
        $editForm = $this->createEditForm($projectId, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('test', array(
                'projectId' => $projectId,
            )));
        }

        return $this->render('CoralScrumMainBundle:Test:edit.html.twig', array(
            'entity'      => $entity,
            'projectId'   => $projectId,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Test entity.
     *
     */
    public function deleteAction($projectId, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoralScrumMainBundle:Test')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Test entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('test', array(
            'projectId' => $projectId,
        )));
    }

    /**
     * Creates a form to delete a Test entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($projectId, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('test_delete', array(
                'id' => $id,
                'projectId' => $projectId,
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
