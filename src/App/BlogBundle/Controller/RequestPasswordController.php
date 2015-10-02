<?php

namespace App\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\User\Password\RequestPassword;

/**
 * @Route("/")
 */
class RequestPasswordController extends Controller
{
    /**
     * @Route("/request-password", name="request_password")
     * @Method("GET|POST")
     */
    public function requestPasswordAction(Request $request)
    {
        $form = $this->createForm('request_password_form', new RequestPassword());

        if ($this->getRequestPasswordFormHandler()->handle($form, $request)) {
            $this->addFlash('success', 'A mail has been sent to your mailbox to reset your password.');
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('user/request-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \App\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getRequestPasswordFormHandler()
    {
        return $this->container->get('app_core.request_password.handler');
    }
}
