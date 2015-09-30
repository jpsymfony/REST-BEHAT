<?php

namespace App\CoreBundle\User\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\UserInterface;
use App\CoreBundle\User\Manager\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\CoreBundle\AppCoreEvents;
use App\CoreBundle\Event\UserDataEvent;

class UserManager implements UserManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param ObjectManager                 $manager
     * @param EncoderFactoryInterface       $encoderFactory
     * @param EventDispatcherInterface      $dispatcher
     * @param UserPasswordEncoderInterface  $encoder
     */
    public function __construct(
    ObjectManager $manager, EncoderFactoryInterface $encoderFactory, EventDispatcherInterface $dispatcher
    )
    {
        $this->objectManager = $manager;
        $this->encoderFactory = $encoderFactory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function createUser(UserInterface $user)
    {
        $user->encodePassword($this->encoderFactory->getEncoder($user));
        $this->persistAndFlushUser($user);

        $this->dispatcher->dispatch(
            AppCoreEvents::NEW_ACCOUNT_CREATED,
            new UserDataEvent($user)
        );
    }

    /**
     * @param UserInterface $user
     */
    private function persistAndFlushUser(UserInterface $user)
    {
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }
}