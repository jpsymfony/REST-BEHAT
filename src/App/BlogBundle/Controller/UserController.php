<?php

namespace App\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/account")
 */
class UserController extends Controller
{
    /**
     * @Route("/dashboard", name="user_dashboard")
     * @Method("GET|POST")
     */
    public function dashboardAction()
    {
        return $this->render('user/dashboard.html.twig');
    }
}
