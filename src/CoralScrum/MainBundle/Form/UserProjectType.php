<?php

namespace CoralScrum\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserProjectType extends AbstractType
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
            //->add('user')
            ->add('user', 'entity', array(
                'class' => 'CoralScrumUserBundle:User',
                'label' => 'User',
                'required' => true,
                'query_builder' => function(\CoralScrum\UserBundle\Entity\UserRepository  $er) use ($projectId) {
                    $in = $er->createQueryBuilder('u2');
                    $in ->select('u2.id')
                        ->join('u2.userproject', 'us_p')
                        ->where('us_p.project = :projectId');

                    $qb = $er->createQueryBuilder('u');
                    $qb->where($qb->expr()->notIn('u.id', $in->getDQL()))
                       ->setParameter('projectId', $projectId)
                       ->orderBy('u.username', 'ASC');
                    return $qb;
                },
            ))
            //*/
            ->add('accountType', 'choice', array(
                'choices'  => array(
                    'Developer'     => 'Developer',
                    'Scrum Master'  => 'Scrum Master',
                    'Project Owner' => 'Project Owner'),
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
            'data_class' => 'CoralScrum\MainBundle\Entity\UserProject',
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
        return 'coralscrum_mainbundle_userproject';
    }
}
