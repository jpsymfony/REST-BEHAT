<?php

namespace App\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\User\Password\ResetPassword;
use App\CoreBundle\User\Manager\UserManagerInterface;

class ResetPasswordType extends AbstractType
{
    /**
     *
     * @var UserManagerInterface $handler
     */
    private $handler;

    /**
     *
     * @var Request $request
     */
    private $request;

    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager, Request $request)
    {
        $this->handler = $userManager;
        $this->request = $request;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'repeated', array(
            'first_name'  => 'password',
            'second_name' => 'confirm',
            'type'        => 'password',
        ));
        $builder->add('Reset Password', 'submit');

        $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                if (!$data instanceof ResetPassword) {
                    throw new \RuntimeException('ChangePassword instance required.');
                }
                $token = $this->request->query->get('token');

                if (!$token) {
                   throw new \Exception('Incorrect Token.');
                }

                $user = $this->handler->getUserByConfirmationToken($token);

                if (!$user) {
                   throw new \Exception('User not identified in our database with this token.');
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\CoreBundle\User\Password\ResetPassword',
        ]);
    }

    public function getName()
    {
        return 'reset_password_form';
    }
} 
