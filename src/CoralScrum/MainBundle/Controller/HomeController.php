<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoralScrumMainBundle::index.html.twig');
    }
}
