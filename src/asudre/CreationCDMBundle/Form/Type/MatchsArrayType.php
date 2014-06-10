<?php

namespace asudre\CreationCDMBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use asudre\CreationCDMBundle\Form\Type\MatchsType;

class MatchsArrayType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('matchs', 'collection', array('type' => new MatchsType()));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'asudre\CreationCDMBundle\Form\Entity\MatchsCollection',
		));
	}

	public function getName()
	{
		return 'matchsArray';
	}

}