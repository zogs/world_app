<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Entity\Location;

class ImportController extends Controller
{
 
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('file',FileType::class,array(
                    'label' => "Select the file you want to import:",
                    'constraints' => array(
                        new FileConstraint(array(
                            'maxSize' => '30M'
                            ))
                        )
                ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {

            //get form data
            $file = $form->get('file')->getData();

            //get world exporter
            $importer = $this->container->get('world.importer');

            //export and get file url          
            $importer->import($file);

            //flash
            $this->get('flashbag')->add("Les données ont été importés !</a>");
            return $this->redirectToRoute('world_import');

        }  


        $this->get('flashbag')->add("Comme les fichiers sont volumineux, il vaut mieux passer par la console : php bin/console world:import:sql 'dbname' 'file'",'info');

        return $this->render('ZogsWorldBundle:Import:index.html.twig',array(
            'form'=> $form->createView(),
            ));
    }

}
