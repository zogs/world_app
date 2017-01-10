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
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\ORM\EntityManager;

class CountryType extends AbstractType
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
            ->add('code', TextType::class, array())
            ->add('lang', TextType::class, array())
            ->add('name', TextType::class, array())
            ->add('region', IntegerType::class, array())
            ->add('iso_2', TextType::class, array())
            ->add('iso_3', TextType::class, array())
            ->add('iso_4', TextType::class, array())
            ->add('iso_numeric', TextType::class, array())
            ->add('internet_domain', TextType::class, array('required' => false))
            ->add('phone_code', TextType::class, array('required' => false))
            ->add('included_in_country', TextType::class, array('required' => false))
            ->add('description', TextType::class, array('required' => false))
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
            'data_class' => 'Zogs\WorldBundle\Entity\Country', 
            'translation_domain' => 'ZogsWorldBundle'           
        ));

    }


}
