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
        $taskId = $options['taskId'];
        $sprintId = $options['sprintId'];
        $projectId = $options['projectId'];

        if (is_null($sprintId))
            throw new \LogicException('SprintId option is required.');

        if (is_null($projectId))
            throw new \LogicException('ProjectId option is required.');

        $builder
            ->add('title')
            ->add('description')
            ->add('duration', 'integer', array(
                'label' => 'Duration (days)',
                'attr' => array('min' => 0)
            ))
            /*
            ->add('state', 'choice', array(
                'choices'  => array(
                    0 => 'To Do',
                    1 => 'In Progress',
                    2 => 'Done'),
                'required' => true))
            ->add('creationDate')
            ->add('startDate')
            ->add('endDate', 'datetime', array(
                'required' => false,
            ))
            */
            ->add('isBug', 'checkbox', array(
                'required' => false))
            ->add('commit', 'text', array(
                'label'    => 'Commit ID',
                'required' => false))
            ->add('userStory', 'entity', array(
                'class'    => 'CoralScrumMainBundle:UserStory',
                'label'    => 'User Story',
                'multiple' => false,
                'query_builder' => function(\CoralScrum\MainBundle\Entity\UserStoryRepository  $er) use ($sprintId) {
                    return $er->createQueryBuilder('u')
                              ->join('u.sprint', 'sp')
                              ->where('sp.id = :sprintId')
                              ->setParameter('sprintId', $sprintId)
                              ->orderBy('u.id', 'ASC');
                },
            ))
            ->add('user', 'entity', array(
                'class'    => 'CoralScrumUserBundle:User',
                'label'    => 'Assign to',
                'multiple' => true,
                'query_builder' => function(\CoralScrum\UserBundle\Entity\UserRepository  $er) use ($projectId) {
                    return $er->createQueryBuilder('u')
                              ->join('u.userproject', 'us_p')
                              ->where('us_p.project = :projectId')
                              ->setParameter('projectId', $projectId)
                              ->orderBy('u.username', 'ASC');
                },
            ))
            ->add('dependency', 'entity', array(
                'class'    => 'CoralScrumMainBundle:Task',
                'label'    => 'Dependencies',
                'multiple' => true,
                'query_builder' => function(\CoralScrum\MainBundle\Entity\TaskRepository  $er) use ($taskId, $sprintId) {
                    return $er->createQueryBuilder('t')
                              ->join('t.userStory', 'us')
                              ->join('us.sprint', 'sp')
                              ->where('t.id != :taskId')
                              ->andWhere('sp.id = :sprintId')
                              ->setParameters(array(
                                'taskId'   => $taskId,
                                'sprintId' => $sprintId,
                              ))
                              ->orderBy('t.id', 'ASC');
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
            'data_class' => 'CoralScrum\MainBundle\Entity\Task',
            'sprintId'   => null,
            'projectId'  => null,
            'taskId'     => 0,
        ));

        $resolver->setRequired(array(
            'sprintId',
            'projectId',
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
