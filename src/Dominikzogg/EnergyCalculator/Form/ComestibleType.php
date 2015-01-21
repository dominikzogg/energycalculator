<?php

namespace Dominikzogg\EnergyCalculator\Form;

use Dominikzogg\EnergyCalculator\Entity\Comestible;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComestibleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('calorie', 'number')
            ->add('protein', 'number')
            ->add('carbohydrate', 'number')
            ->add('fat', 'number')
            ->add('defaultValue', 'number', array('required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => Comestible::class,
        ));
    }

    public function getName()
    {
        return 'comestible_edit';
    }
}
