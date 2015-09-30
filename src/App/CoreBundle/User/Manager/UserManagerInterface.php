<?php

namespace App\CoreBundle\User\Manager;

use App\CoreBundle\Entity\UserInterface;

interface UserManagerInterface
{
    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function createUser(UserInterface $user);
} 
