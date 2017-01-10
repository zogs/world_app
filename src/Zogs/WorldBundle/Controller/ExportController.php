<?php

namespace Zogs\WorldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Zogs\WorldBundle\Entity\City;
use Zogs\WorldBundle\Entity\Location;

class ExportController extends Controller
{
 
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('countries',EntityType::class,array(
                    'multiple'=> true,
                    'expanded'=> true,
                    'class' => 'ZogsWorldBundle:Country',
                    'choice_label' => 'name',
                    'mapped'=> true,
                    'label' => "Select countries you want to export:",
                ))
            ->add('droptable',CheckboxType::class,array(
                'required'=> false,
                'label' => 'DROP TABLE BEFORE IMPORT ?',
                'data' => false,
                ))
            ->add('createtable',CheckboxType::class,array(
                'required'=> false,
                'label' => 'CREATE TABLE ?',
                'data' => false,
                ))
            ->add('inserts',CheckboxType::class,array(
                'required' => false,
                'label' => 'FORCE INSERT STATEMENT ?',
                'data' => false,
                ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {

            //get form data
            $countries = $form->get('countries')->getData();
            $drop = $form->get('droptable')->getData();
            $create = $form->get('createtable')->getData();
            $inserts = $form->get('inserts')->getData();

            //get world exporter
            $exporter = $this->container->get('world.exporter');
            //set options
            $exporter->dropTable($drop); // add statement to drop table before insert
            $exporter->createTable($create); // add statement to create tables
            $exporter->forceInserts($inserts); // force INSERT statement if possible
            //export and get file url
            $exporter->setCountries($countries);            
            $filename = $exporter->export();            
            $filepath = $exporter->getWebPath().$filename;

            //flash
            $file_url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $filepath;
            $this->get('flashbag')->add("Le fichier a été généré. <a href='".$file_url."' download>Cliquez ici pour le télécharger !</a>");
            return $this->redirectToRoute('world_export');

        }  

        return $this->render('ZogsWorldBundle:Export:index.html.twig',array(
            'form'=> $form->createView(),
            ));
    }

}
