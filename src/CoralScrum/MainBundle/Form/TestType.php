<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('input')
            ->add('testCase')
            ->add('expectedResult')
            ->add('date')
            ->add('comment')
            ->add('userStory')
            ->add('tester')
            ->add('state', 'choice', array(
                'choices'  => array(
                    '0' => 'Not tested',
                    '1' => 'Test passed',
                    '2' => 'Test failed'),
                'required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoralScrum\MainBundle\Entity\Test'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coralscrum_mainbundle_test';
    }
}
