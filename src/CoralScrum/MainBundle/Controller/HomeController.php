<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
    		return $this->redirect($this->generateUrl('project'));
    	}
        return $this->render('CoralScrumMainBundle::home.html.twig');
    }
}
