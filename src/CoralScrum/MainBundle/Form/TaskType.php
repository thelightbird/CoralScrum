<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('duration')
            ->add('state', 'choice', array(
                'choices'  => array(
                    'To Do' => 'To Do',
                    'In Progress' => 'In Progress',
                    'Done' => 'Done'),
                'required' => true))
            //->add('creationDate')
            ->add('startDate')
            ->add('endDate')
            ->add('isBug')
            ->add('commit', 'text', array(
                'label' => 'Commit ID'))
            ->add('userStory')
            ->add('user', 'entity', array(
                'class' => 'CoralScrumUserBundle:User',
                'label' => 'Assign to',
                'multiple' => true,
                /*
                'query_builder' => function(\CoralScrum\UserBundle\Entity\UserRepository  $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id = 1')
                        ->orderBy('u.id', 'ASC');
                },
                //*/
            ))
            ->add('dependency')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoralScrum\MainBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coralscrum_mainbundle_task';
    }
}
