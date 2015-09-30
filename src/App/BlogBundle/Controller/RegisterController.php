<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\BlogBundle\Form\Type\RegistrationType;
use App\CoreBundle\User\Registration\Registration;

/**
 * Controller used to manage the application security.
 * See http://symfony.com/doc/current/cookbook/security/form_login_setup.html.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class RegisterController extends Controller
{

    /**
     * @Route("/register", name="security_register_form")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(new RegistrationType(), new Registration());

        if ($this->getRegistrationFormHandler()->handle($form, $request)) {
            return $this->redirectToRoute('security_login_form');
        }

        return $this->render('security/register.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * @return \App\CoreBundle\Form\Handler\FormHandlerInterface
     */
    protected function getRegistrationFormHandler()
    {
        return $this->container->get('app.registration.handler');
    }
}