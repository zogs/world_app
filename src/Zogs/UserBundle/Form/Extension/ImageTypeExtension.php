<?php

namespace Zogs\UserBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageTypeExtension extends AbstractTypeExtension
{
	/**
	 * Retourne le nom du champ qui est étendu
	 */
	public function getExtendedType()
	{
		return FileType::class;
	}

	/**
	 * Ajoute l'option "image_path" au champ
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefined('image_path');
	}

	/**
	 * Passe l'url de l'image à la vue
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		if(array_key_exists('image_path',$options)){
			$parentData = $form->getParent()->getData();

			if( null != $parentData) {
				$accessor = PropertyAccess::createPropertyAccessor();
				$imageUrl = $accessor->getValue($parentData, $options['image_path']);
			} else {
				$imageUrl = null;
			}

			$view->vars['image_url'] = $imageUrl;
		}
	}
}