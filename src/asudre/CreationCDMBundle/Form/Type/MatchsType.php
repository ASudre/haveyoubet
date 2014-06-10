<?php

namespace asudre\CreationCDMBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use asudre\CDM14Bundle\Entity\Matchs;

class MatchsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('equipe1', 'entity', array(
        	'class' => 'asudreCDM14Bundle:Equipes',
			'disabled' => true
		));
		$builder->add('equipe2', 'entity', array(
        	'class' => 'asudreCDM14Bundle:Equipes',
			'disabled' => true
		));
		$builder->add('date', 'datetime');
		$builder->add('groupe', 'hidden');
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'asudre\CDM14Bundle\Entity\Matchs',
		));
	}
	
	public function getName()
	{
		return 'matchs';
	}

}