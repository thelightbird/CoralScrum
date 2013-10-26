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
        $projectId = $options['projectId'];

        if(is_null($projectId))
            throw new \LogicException('ProjectId option is required.');

        $builder
            ->add('title')
            ->add('input')
            ->add('testCase')
            ->add('expectedResult')
            ->add('date')
            ->add('comment')
            ->add('userStory', 'entity', array(
                'class' => 'CoralScrumMainBundle:UserStory',
                'label' => 'User Story',
                'query_builder' => function(\CoralScrum\MainBundle\Entity\UserStoryRepository  $er) use ($projectId) {
                    return $er->createQueryBuilder('us')
                              ->where('us.project = :projectId')
                              ->setParameter('projectId', $projectId);
                },
            ))
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
            'data_class' => 'CoralScrum\MainBundle\Entity\Test',
            'projectId'  => null,
        ));

        $resolver->setRequired(array(
            'projectId',
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
