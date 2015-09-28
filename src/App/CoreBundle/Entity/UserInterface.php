<?php

namespace App\CoreBundle\Entity;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface as SecurityUserInterface;

interface UserInterface extends SecurityUserInterface
{
    public function encodePassword(PasswordEncoderInterface $encoder);
} 
