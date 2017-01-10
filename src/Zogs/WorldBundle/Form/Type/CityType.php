<?php

namespace Zogs\WorldBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\ORM\EntityManager;

class CityType extends AbstractType
{
    public $em;
    private $router;
    private $options;

    public function __construct(EntityManager $em,Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->em;

        $builder   
            ->add('char_code', TextType::class, array())
            ->add('ufi', IntegerType::class, array())
            ->add('uni', IntegerType::class, array())
            ->add('cc1', TextType::class, array())
            ->add('dsg', TextType::class, array())
            ->add('adm1', TextType::class, array('required' => false))
            ->add('adm2', TextType::class, array('required' => false))
            ->add('adm3', TextType::class, array('required' => false))
            ->add('adm4', TextType::class, array('required' => false))
            ->add('nt', TextType::class, array())
            ->add('lc', TextType::class, array())
            ->add('shortform', TextType::class, array())
            ->add('fullname', TextType::class, array())
            ->add('fullnamed', TextType::class, array())
            ->add('characters', TextType::class, array())
            ->add('latitude', NumberType::class, array())
            ->add('longitude', NumberType::class, array())
            ->add('dmslat', IntegerType::class, array())
            ->add('dmslong', IntegerType::class, array())
            ->add('soundex', TextType::class, array())
            ->add('metaphone', TextType::class, array())
            ->add('cp', TextType::class, array())
            ->add('pop', IntegerType::class, array())
            ->add('pop_order', IntegerType::class, array())
            ->add('sfc', IntegerType::class, array())
            ->add('sfc_order', IntegerType::class, array())
            ->add('submit', SubmitType::class, array())
            ;

        $this->options = $options;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        $builder->addEventListener(FormEvents::SUBMIT, array($this, 'onSubmit'));
        $builder->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
      
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $location = $event->getData();         
    }    
    
    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData(); 
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }

    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zogs\WorldBundle\Entity\City', 
            'translation_domain' => 'ZogsWorldBundle'           
        ));

    }


}
