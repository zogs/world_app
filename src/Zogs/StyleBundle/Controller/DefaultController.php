<?php

namespace Zogs\StyleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ZogsStyleBundle:Default:index.html.twig', array('name' => $name));
    }
}
