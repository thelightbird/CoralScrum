<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserProjectEditType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', array(
                'class' => 'CoralScrumUserBundle:User',
                'label' => 'User',
                'read_only' => true,
                'disabled' => true
            ))
            ->add('accountType', 'choice', array(
                'choices'  => array(
                    'Project Owner' => 'Project Owner',
                    'Scrum Master'  => 'Scrum Master',
                    'Developer'     => 'Developer'),
                'required' => true
            ))
            //->add('isAccept')
            //->add('project')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoralScrum\MainBundle\Entity\UserProject'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coralscrum_mainbundle_userproject';
    }
}
