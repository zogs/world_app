<?php

namespace Zogs\DatabaseToolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ConvertController extends Controller
{
	public function indexAction()
	{
		return $this->render('ZogsDatabaseToolBundle:Default:index.html.twig');
	}

	public function ConvertOneAction()
	{
		$doctrineEntityName = $this->getRequest()->query->get('table');

		$converter = $this->get('db.table_converter');

		$converter->importConfig();

		$results = $converter->convertOne($doctrineEntityName);

              if(!empty($results['success'])) $this->get('flashbag')->add("Bravo, ".count($results['success'])." entités créés! ",'success');              
              if(!empty($results['errors'])) $this->get('flashbag')->add("Il y a eu ".count($results['errors'])." erreurs...",'warning');

		return $this->render('ZogsDatabaseToolBundle:Default:results.html.twig', array('success' => $results['success'],'errors'=> $results['errors']));
	}

    public function ConvertAllAction()
    {
   
       $converter = $this->get('db.table_converter');
       $purger = $this->get('db.table_purger');

       $purger->purge();

       $converter->importConfig();
       $results = $converter->convertAll();

        if(!empty($results['success'])) $this->get('flashbag')->add("Bravo, ".count($results['success'])." entités créés! ",'success');              
        if(!empty($results['errors'])) $this->get('flashbag')->add("Il y a eu ".count($results['errors'])." erreurs...",'warning');

       return $this->render('ZogsDatabaseToolBundle:Default:results.html.twig', array('success' => $results['success'],'errors'=> $results['errors']));

    }

    public function PurgeAction()
    {
      $purger = $this->get('db.table_purger');
      $purger->purge();

      $this->get('flashbag')->add('La base de donnée <strong><i>'.$purger->getDatabase().'</i></strong> à été purgé','success');

    	return $this->redirect($this->generateUrl('db_convert_index'));
    }


}
