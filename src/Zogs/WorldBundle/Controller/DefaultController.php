<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Entity\Location;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {        

    	$countries = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findAllCountry();

        return $this->render('ZogsWorldBundle:Default:index.html.twig',array(
            'countries' => $countries
            ));
    }

}
