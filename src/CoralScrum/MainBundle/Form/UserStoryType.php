<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserStoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fibonacci = array(
            '0' => 0, '1' => 1, '1' => 1, '2' => 2, '3' => 3, '5' => 5, '8' => 8, 
            '13' => 13, '21' => 21, '34' => 34, '55' => 55, '89' => 89, '144' => 144, 
            '233' => 233, '377' => 377, '610' => 610, '987' => 987
        );
        $builder
            ->add('title')
            ->add('description')
            ->add('priority', 'choice', array(
                'choices' => $fibonacci,
            ))
            ->add('difficulty', 'choice', array(
                'choices' => $fibonacci,
            ))
            //->add('isFinished')
            //->add('isValidated')
            //->add('project')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoralScrum\MainBundle\Entity\UserStory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coralscrum_mainbundle_userstory';
    }
}
