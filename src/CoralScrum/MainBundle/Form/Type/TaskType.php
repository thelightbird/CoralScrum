<?php

namespace CoralScrum\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //dependency
            //task
            //project
            //user
            //userStory
            ->add('priority')
            ->add('title')
            ->add('description')
            ->add('duration')
            ->add('state')
            //->add('creationDate')
            ->add('startDate')
            ->add('endDate')
            ->add('testDate')
            ->add('isBug')
            ;
    }

    public function getName()
    {
        return 'coralscrum_mainbundle_tasktype';
    }
}
