<?php

namespace App\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\CoreBundle\User\Manager\UserManagerInterface;
use App\CoreBundle\User\Password\RequestPassword;

class RequestPasswordType extends AbstractType
{
    /**
     *
     * @var UserManagerInterface $handler
     */
    private $handler;

    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->handler = $userManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifier');

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
            $data = $event->getData();

            if (!$data instanceof RequestPassword) {
                throw new \RuntimeException('RequestPassword instance required.');
            }
            $identifier = $data->getIdentifier();
            $form = $event->getForm();

            if (!$identifier || count($form->getErrors(true))) {
                return;
            }

            $user = $this->handler->getUserByIdentifier($identifier);

            if (null == $user) {
                $form->get('identifier')->addError(new FormError('User not present in our database'));
                return;
            } else {
                $data->setUser($user);

                if ($user->getIsAlreadyRequested() && null != $user->getConfirmationToken()) {
                    $form->get('identifier')->addError(new FormError('You already requested for a new password'));
                    return;
                }
            }
        }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\CoreBundle\User\Password\RequestPassword',
        ]);
    }

    public function getName()
    {
        return 'request_password_form';
    }
}