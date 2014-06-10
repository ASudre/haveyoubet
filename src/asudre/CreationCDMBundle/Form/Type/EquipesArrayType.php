<?php

namespace asudre\CreationCDMBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use asudre\CreationCDMBundle\Form\Type\EquipesType;

class EquipesArrayType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('equipes', 'collection', array('type' => new EquipesType()));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'asudre\CreationCDMBundle\Form\Entity\EquipesCollection',
		));
	}

	public function getName()
	{
		return 'equipesArray';
	}

}