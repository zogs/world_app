<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Zogs\WorldBundle\Entity\Country;
use Zogs\WorldBundle\Form\Type\CountryType;

class CountryController extends Controller
{


    public function listAction(Request $request) {

        $countries = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findAllCountry();

        return $this->render('ZogsWorldBundle:Country:list.html.twig', array(
            'countries' => $countries,
            ));
    }

    public function viewAction(Country $country)
    {        
        return $this->render('ZogsWorldBundle:Country:view.html.twig',array(
            'country'=>$country
            ));
    }

    public function editAction(Country $country, Request $request)
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if($form->isValid()) {

            $country = $form->getData();
            $em = $this->get('world.orm.manager');
            $em->persist($country);
            $em->flush();

            $this->get('flashbag')->add('Country has been succesfully saved !!','success');
        }       

        return $this->render('ZogsWorldBundle:Country:edit.html.twig', array(
            'country' => $country,
            'form' => $form->createView()
            ));
    }

}
