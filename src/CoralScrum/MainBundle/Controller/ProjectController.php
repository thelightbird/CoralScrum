<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\UserBundle\Entity\User;
use CoralScrum\MainBundle\Entity\Project;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends Controller
{
    public function selectionAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('CoralScrumMainBundle:Project')->getVisibleProject(1);

        /*
        $repository = $this->getDoctrine()
            ->getRepository('CoralScrumMainBundle:Project');
        
        $projects2 = $repository->findBy(
            array(
                'owner' => 1,
                'isPublic' => 1
                // && relation in user_project
            ),
            array('id' => 'ASC')
        );
        */

        return $this->render('CoralScrumMainBundle:Project:selection.html.twig', array(
            'projects' => $projects
        ));
    }

    public function settingsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('CoralScrumMainBundle:Project')->find($id);
        if ( ! $project ) {
            throw $this->createNotFoundException('Unable to find Project.');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();
        if( ! is_object($user) ) {
            throw new AccessDeniedException('You are not logged in.');
        }

        if( $project->getOwner()->getId() != $user->getId() ) {
            throw new AccessDeniedException('You are not authorized to access this page.');
        }

        return $this->render('CoralScrumMainBundle:Project:settings.html.twig');
    }

    public function addAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        if( ! is_object($user) ) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $project = new Project();
        $project->setOwner($user);

        $formBuilder = $this->createFormBuilder($project);
        $formBuilder
            ->add('name', 'text')
            ->add('isPublic', 'checkbox', array(
                'label'     => 'public',
                'required'  => false,
                'data' => true,))
            ;
        $form = $formBuilder->getForm();

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->handleRequest($request);

            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();
                
                return $this->redirect($this->generateUrl('CSM_project_selection'));
            }
        }

        return $this->render('CoralScrumMainBundle:Project:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
