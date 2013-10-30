<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
        return $this->render('CoralScrumMainBundle::home.html.twig');
    }
}
