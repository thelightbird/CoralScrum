<?php

namespace CoralScrum\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoralScrumMainBundle::index.html.twig');
    }

    public function registerAction()
    {
        return $this->render('CoralScrumMainBundle::register.html.twig');
    }

    public function loginAction()
    {
        return $this->render('CoralScrumMainBundle::login.html.twig');
    }
}
