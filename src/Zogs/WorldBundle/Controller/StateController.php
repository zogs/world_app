<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Entity\State;
use Zogs\WorldBundle\Form\Type\StateType;
use Zogs\WorldBundle\Entity\Country;
use Zogs\WorldBundle\Entity\Location;

class StateController extends Controller
{


    public function listRegionAction(Country $country) {

        $regions = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:State')->findStatesByParent('ADM1',$country->getCode());

        if(empty($regions)) return $this->forward('ZogsWorldBundle:City:list',array('state' => $country));

        return $this->render('ZogsWorldBundle:State:list.html.twig', array(
            'states' => $regions,
            'level' => 'world.region.label',
            'child_route' => 'world_list_departement',
            'parent' => $country
            ));
    }

    public function listDepartementAction(State $state) {

        $region = $state;

        $country = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findCountryByCode($region->getCc1());

        $departements =$this->get('world.orm.manager')->getRepository('ZogsWorldBundle:State')->findStatesByParent('ADM2',$country->getCode(),$region->getAdmCode());

        if(empty($departements)) return $this->forward('ZogsWorldBundle:City:list',array('state' => $state));

        return $this->render('ZogsWorldBundle:State:list.html.twig', array(
            'states' => $departements,
            'level' => 'world.departement.label',
            'child_route' => 'world_list_district',
            'parent' => $region
            ));
    }

    public function listDistrictAction(State $state) {

        $departement = $state;

        $country = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findCountryByCode($departement->getCc1());

        $districts = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:State')->findStatesByParent('ADM3',$country->getCode(),$departement->getAdmCode());

        if(empty($districts)) return $this->forward('ZogsWorldBundle:City:list',array('state' => $state));

        return $this->render('ZogsWorldBundle:State:list.html.twig', array(
            'states' => $districts,
            'level' => 'world.district.label',
            'child_route' => 'world_list_division',
            'parent' => $departement
            ));
    }

    public function listDivisionAction(State $state) {

        $district = $state;

        $country = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:Country')->findCountryByCode($district->getCc1());

        $divisions = $this->get('world.orm.manager')->getRepository('ZogsWorldBundle:State')->findStatesByParent('ADM4',$country->getCode(),$district->getAdmCode());

        return $this->render('ZogsWorldBundle:State:list.html.twig', array(
            'states' => $divisions,
            'level' => 'world.division.label',
            'child_route' => 'world_list_city',
            'parent' => $district
            ));
    }

    public function viewAction(Country $country)
    {        
        return $this->render('ZogsWorldBundle:Country:view.html.twig',array(
            'country'=>$country
            ));
    }

    public function editAction(State $state, Request $request)
    {
        $form = $this->createForm(StateType::class, $state);

        $form->handleRequest($request);

        if($form->isValid()) {

            $state = $form->getData();
            $em = $this->get('world.orm.manager');
            $em->persist($state);
            $em->flush($state);

            $this->get('flashbag')->add('State have been successfuly saved !', 'success');
        }

        return $this->render('ZogsWorldBundle:State:edit.html.twig', array(
            'state' => $state,
            'form' => $form->createView()
            ));
    }

}
