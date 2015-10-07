<?php

namespace App\CoreBundle\EventListener;

use Twig_Environment;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\CoreBundle\Event\UserDataEvent;
use App\CoreBundle\User\Manager\UserManagerInterface;

class SendRequestPasswordMailListener
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $templating;

    /**
     *
     * @var RouterInterface $router
     */
    protected $router;

    /**
     *
     * @var TokenGeneratorInterface $tokenGenerator
     */
    protected $tokenGenerator;

    /**
     * @var UserManagerInterface $userManager
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $template;

    /**
     * @var string $from
     */
    protected $from;

    /**
     * @param \Swift_Mailer $mailer
     * @param Twig_Environment $templating
     * @param $template
     * @param $from
     */
    public function __construct(\Swift_Mailer $mailer, Twig_Environment $templating, RouterInterface $router,
                                TokenGeneratorInterface $tokenGenerator, UserManagerInterface $userManager, $template,
                                $from)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
        $this->tokenGenerator = $tokenGenerator;
        $this->userManager = $userManager;
        $this->template = $template;
        $this->from = $from;
    }

    /**
     * @param UserDataEvent $event
     */
    public function onRequestedPassword(UserDataEvent $event)
    {
        $user = $event->getUser();
        $token = $this->tokenGenerator->generateToken();
        $this->userManager->updateConfirmationTokenUser($user, $token);

        $message = \Swift_Message::newInstance()
            ->setCharset('UTF-8')
            ->setSubject($this->templating->loadTemplate($this->template)->renderBlock('subject', []))
            ->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody($this->templating->loadTemplate($this->template)->renderBlock('body',
                [
                'username' => $user->getUsername(),
                'request_link' => $this->router->generate('reset_password',
                    ['token' => $token], true)
            ])
        );

        $this->mailer->send($message);
    }
}