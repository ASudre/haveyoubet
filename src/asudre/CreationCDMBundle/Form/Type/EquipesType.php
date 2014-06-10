<?php

namespace asudre\CreationCDMBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use asudre\CDM14Bundle\Entity\Equipes;

class EquipesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom');
		$builder->add('groupe', 'hidden');
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'asudre\CDM14Bundle\Entity\Equipes',
		));
	}
	
	public function getName()
	{
		return 'equipes';
	}

}