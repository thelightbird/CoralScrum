<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CoralScrum\MainBundle\Entity\Project;
use CoralScrum\MainBundle\Entity\UserProject;
use CoralScrum\MainBundle\Form\ProjectType;
use CoralScrum\MainBundle\Services\Security;

/**
 * Project controller.
 *
 */
class ProjectController extends Controller
{
    public function getConnectedUser()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        return $user;
    }

    /**
     * Lists all Project entities.
     *
     */
    public function indexAction()
    {
        $user = $this->getConnectedUser();

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
        $user = $this->getConnectedUser();

        $project = new Project();
        $project->setOwner($user);
        $form = $this->createCreateForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);

            $userProject = new UserProject();
            $userProject->setProject($project);
            $userProject->setUser($user);
            $userProject->setIsAccept(true);
            $userProject->setAccountType("Project Owner");
            $em->persist($userProject);

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
        $user = $this->getConnectedUser();

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
        $this->get('csm_security')->checkUserMembership($projectId);

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('CoralScrumMainBundle:Project')->findOneByIdJoinedToUser($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        return $this->render('CoralScrumMainBundle:Project:show.html.twig', array(
            'entity'        => $project,
            'projectId'     => $projectId,
        ));
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     */
    public function editAction($projectId)
    {
        $user = $this->get('csm_security')->isCreator($projectId);

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
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
        $user = $this->get('csm_security')->isCreator($projectId);

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($projectId);
        $editForm = $this->createEditForm($project);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('project_edit', array('projectId' => $projectId)));
        }

        return $this->render('CoralScrumMainBundle:Project:edit.html.twig', array(
            'entity'      => $project,
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
        $user = $this->get('csm_security')->isCreator($projectId);

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $em->remove($project);
        $em->flush();

        return $this->redirect($this->generateUrl('project'));
    }

    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param mixed $projectId The entity id
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
