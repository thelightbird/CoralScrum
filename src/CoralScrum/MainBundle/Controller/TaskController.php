<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CoralScrum\UserBundle\Entity\User;
use CoralScrum\MainBundle\Entity\Task;
use CoralScrum\MainBundle\Entity\Project;
use CoralScrum\MainBundle\Form\Type\TaskType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskController extends Controller
{
    public function listAction($id)
    {
        return $this->render('CoralScrumMainBundle:Task:list.html.twig');
    }

    public function addAction($id)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        if( ! is_object($user) ) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $task = new Task();
        //$project->setOwner($user);

        $form = $this->createForm(new TaskType(), $task);
/*

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
*/
        return $this->render('CoralScrumMainBundle:Task:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
