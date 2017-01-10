<?php

namespace Zogs\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AvatarType extends AbstractType
{

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
                $now = new \DateTime('now');

                $builder
                    ->add('file',FileType::class,array(
                        'required'=> false,
                        'label'=> "Votre avatar",
                        'image_path'=> 'webPath',
                    ))
                    ->add('updated','hidden',array(
                        'data'=> $now->format('Y-m-d H:i:s')
                    ))
                    ;

        }

        public function configureOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                    'data_class' => 'Zogs\UserBundle\Entity\Avatar',
            ));
        }

}
