<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Form\Type\CityType;
use Zogs\WorldBundle\Entity\Location;
use Zogs\WorldBundle\Entity\State;
use Zogs\WorldBundle\Form\Type\CityToLocationType;

class CityController extends Controller
{

    public function viewAction(City $city)
    {        
        return $this->render('ZogsWorldBundle:City:view.html.twig',array(
            'city'=>$city
            ));
    }

    public function editAction(City $city, Request $request)
    {
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);

        if($form->isValid()) {

            $city = $form->getData();
            $em = $this->get('world.orm.manager');
            $em->persist($city);
            $em->flush();

            $this->get('flashbag')->add('City has been succesfully saved !!','success');
        }       

        return $this->render('ZogsWorldBundle:City:edit.html.twig', array(
            'city' => $city,
            'form' => $form->createView()
            ));
    }

    public function listAction($state)
    {
        if($state instanceof State)
            $cities = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:City')->findCitiesByStateParent($state);
        if($state instanceof Country)
            $cities = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:City')->findByCc1($state->cc1);


        return $this->render('ZogsWorldBundle:City:list.html.twig', array(
            'cities' => $cities,
            'level' => 'world.city.label',
            'parent' => $state,
            ));
    }

    public function searchAction(Request $request)
    {        
        $location = new Location();

        //limit the search to a country (default: France)
        //$country = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findCountryByCode('UK');
        //$location->setCountry($country);

        $form = $this->createForm(CityToLocationType::class,$location);

        $form->handleRequest($request);

        if($form->isValid()){

            $location = $form->getData();                
        }
        
        return $this->render('ZogsWorldBundle:City:search.html.twig',array(
            'form' => $form->createView(),
            'location' => $location
            ));
    }
    
    public function autoCompleteAction($country,$prefix)
    {
        $em = $this->get('world.orm.manager');

        $cities = $em->getRepository('ZogsWorldBundle:City')->findCitiesSuggestions(10,$prefix,$country);

        foreach($cities as $k => $city){

            $city->upperstate = $em->getRepository('ZogsWorldBundle:State')->findStateByCodes($city->getCC1(),$city->getADM1(),$city->getADM2(),$city->getADM3(),$city->getADM4());

            $cities[$k] = array();
            $cities[$k]['name'] = $city->getName();
            $cities[$k]['state'] = $city->upperstate->getName();
            $cities[$k]['token'] = preg_split('/[ -]/',$city->getName().' '.$city->upperstate->getName());
            $cities[$k]['cc1'] = $city->getCc1();
            $cities[$k]['id'] = $city->getId();
            $cities[$k]['value'] = $city->getId();

        }

        return new JsonResponse($cities);
    }

}
