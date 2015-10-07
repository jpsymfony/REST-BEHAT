<?php

namespace App\CoreBundle\EventListener;

use App\CoreBundle\Event\UserDataEvent;
use Twig_Environment;

class SendConfirmationMailListener
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
    public function __construct(\Swift_Mailer $mailer, Twig_Environment $templating, $template, $from)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->template = $template;
        $this->from = $from;
    }

    /**
     * @param UserDataEvent $event
     */
    public function onNewAccountCreated(UserDataEvent $event)
    {
        $message = \Swift_Message::newInstance()
            ->setCharset('UTF-8')
            ->setSubject($this->templating->loadTemplate($this->template)->renderBlock('subject', []))
            ->setFrom($this->from)
            ->setTo($event->getUser()->getEmail())
            ->setBody($this->templating->loadTemplate($this->template)->renderBlock('body', [
                    'username' => $event->getUser()->getUsername()
                ])
            );

        $this->mailer->send($message);
    }
} 