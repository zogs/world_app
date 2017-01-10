<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Entity\Location;
use Zogs\WorldBundle\Form\Type\LocationSelectorType;

use Zogs\WorldBundle\Form\DataTransformer\StatesToLocationTransformer;

class LocationController extends Controller
{
    public function locationSelectAction(Request $request)
    {
        $location = new Location();        

        $form = $this->createForm(LocationSelectorType::class,$location);

        $form->handleRequest($request);        

        if($form->isValid()){
              $location = $form->getData();
        }

        return $this->render('ZogsWorldBundle:Form:test_location_select.html.twig',array(
            'form' => $form->createView(),
            'location' => $location,
            ));
    	
    }


    public function nextGeoLevelAction(Request $request)
    {     
        //entity manager 
        $em = $this->get('world.orm.manager');

        //find current state
        $parent = $em->getRepository('ZogsWorldBundle:Location')->findStateById($request->query->get('level'),$request->query->get('value'));

        //find children of the current state
        $children = $em->getRepository('ZogsWorldBundle:Location')->findChildrenStatesByParent($parent);

        //create html options
        $level = '';
        $options = '';
        if(!empty($children)){
            $options .= '<option value="">'.$this->getSelectBoxHelper($children[0]->getLevel()).'</options>';
            foreach ($children as $child) {
                $options .= '<option value="'.$child->getId().'">'.$child->getName().'</option>';
            }
            $level = $child->getLevel();

        }               

        return new JsonResponse(array(
            'level'=>$level,
            //'location'=>$actual_location->getId(),
            'options'=>$options,
            ));
        
    }

    private function getSelectBoxHelper($level)
    {
        $helpers = array(
            'country'=>"Sélectionnez un pays",
            'region'=>"Sélectionnez une région",
            'departement'=>"Sélectionnez un département",
            'district'=>"Select a district",
            'division'=>"Select a division",
            'city'=>"Sélectionnez une ville"
            );

        return $helpers[$level];
    }

    public function nearestLatLonAction($lat,$lon,$country = null)
    {

        if(!is_numeric($lat) || !is_numeric($lon)) throw new \Exception('lat and lon must be numeric');

        $location = $this->get('world.location_manager')->getLocationFromNearestCityLatLon($lat,$lon,$country);

        dump($location);
        exit();
    }

}
