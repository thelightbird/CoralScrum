<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SprintType extends AbstractType
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
            //->add('project')
            ->add('startDate')
            ->add('duration', 'integer', array(
                'attr' => array('min' => 0)
            ))
            ->add('userStory', 'entity', array(
                'class'    => 'CoralScrumMainBundle:UserStory',
                'label'    => 'User Stories',
                'multiple' => true,
                'query_builder' => function(\CoralScrum\MainBundle\Entity\UserStoryRepository  $er) use ($projectId) {
                    return $er->createQueryBuilder('us')
                              ->where('us.project = :projectId')
                              ->setParameter('projectId', $projectId);
                },
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoralScrum\MainBundle\Entity\Sprint',
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
        return 'coralscrum_mainbundle_sprint';
    }
}
