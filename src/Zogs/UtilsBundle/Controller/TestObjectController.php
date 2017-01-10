<?php

// src/AppBundle/Controller/RedirectingController.php
namespace Zogs\UtilsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Zogs\UserBundle\Entity\User;

class TestObjectController extends Controller
{
	
    public function testObjectChangeAction(Request $request)
    {
       $homme = new \stdClass();
       $homme->type = 'homme';

       $garou = clone $homme;
       $garou->type = 'loup-garou';

       $changes = \Zogs\UtilsBundle\Utils\Object::getChanges($homme,$garou);

       dump($homme);
       dump($garou);
       dump($changes);
       exit();

    }
}