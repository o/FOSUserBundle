<?php

namespace FOS\UserBundle\Document;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserListener implements EventSubscriber
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * Constructor
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getDocument();
        if ($entity instanceof UserInterface) {
            $this->userManager->updateCanonicalFields($entity);
            $this->userManager->updatePassword($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getDocument();
        if ($entity instanceof UserInterface) {
            $this->userManager->updateCanonicalFields($entity);
            $this->userManager->updatePassword($entity);
        }
    }
}