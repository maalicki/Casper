<?php

namespace Polcode\CasperBundle\Security\Core\User\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

interface ExternalProviderInterface {

    /**
     * @return User | null
     */
    public function getUser();

    public function createUser();

    public function updateUserLoginInfo(UserInterface $user);
}
