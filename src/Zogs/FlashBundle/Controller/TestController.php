<?php

namespace Zogs\FlashBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{

    public function testAction()
    {
    	$this->get('flashbag')->add('Le message de succès est bien affiché !','success');
    	$this->get('flashbag')->add("Le message d'erreur apparait aussi !",'danger');
    	$this->get('flashbag')->add("Attention au message warning ...",'warning');
    	$this->get('flashbag')->add("Ah non tout va bien !",'info');
        $this->addFlash('custom','These are defaults message types... But this one has his own type and can be used for other purpose !');

    	return $this->render('ZogsFlashBundle:Default:test.html.twig');
    }

    public function redirectTestAction() 
    {
        $this->get('flashbag')->add('Le message de succès est bien affiché !','success');
        $this->get('flashbag')->add("Le message d'erreur apparait aussi !",'danger');
        $this->get('flashbag')->add("Attention au message warning ...",'warning');
        $this->get('flashbag')->add("Ah non tout va bien !",'info');
        $this->addFlash('custom','These are default alerts messages... But this one has his own type and can be used for other purpose !');

        return $this->redirectToRoute('flashbag_test_redirect_show');
    }

    public function redirectShowAction() 
    {
        return $this->render('ZogsFlashBundle:Default:test.html.twig');
    }

    
}
