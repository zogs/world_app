<?php

namespace Zogs\FlashBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashController extends Controller
{
	private $session;

    public function __construct(Session $session)
    {
    	$this->session = $session;
    }

    public function add($message = '', $type = 'success')
    {
    	$this->session->getFlashBag()->add($type,$message);
    }
    
    public function has($type)
    {
    	if($this->session->getFlashBag()->has($type)){
    		return true;
    	} else {
    		return false;
    	}
    }

    public function get($type)
    {
    	return $this->session->getFlashBag()->get($type);
    }

    public function all()
    {
    	return $this->session->getFlashBag()->all();
    }

    public function clear()
    {
        $this->session->getFlashBag()->clear();        
    }
}
