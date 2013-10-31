<?php
namespace CoralScrum\UserBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use CoralScrum\UserBundle\Entity\User;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserBundle;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginManager implements EventSubscriberInterface
{  
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine)
    {
        $this->securityContext = $securityContext;
        $this->em = $doctrine->getEntityManager();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onSecurityImplicitLogin',
            FOSUserEvents::REGISTRATION_COMPLETED  => 'onRegistrationCompleted'
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $this->updateUserInfos($event, true);
    }

    public function onSecurityImplicitLogin(UserEvent $event)
    {
        $this->updateUserInfos($event, false);
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->updateUserInfos($event, false);
    }

    public function updateUserInfos($event, $registration) {
        $user = $this->securityContext->getToken()->getUser();

        if (!is_object($user)) {
            throw new AccessDeniedException('You are not logged in.');
        }

        $request = $event->getRequest();
        if ($registration) {
            $user->setRegistrationIp($request->getClientIp());
        }
        $user->setLastConnectionIp($request->getClientIp());
        $user->setLastConnectionDate(new \Datetime());

        $this->em->persist($user);
        $this->em->flush();
    }
}