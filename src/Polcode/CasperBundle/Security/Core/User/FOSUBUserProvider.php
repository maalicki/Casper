<?php

namespace Polcode\CasperBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Polcode\CasperBundle\Security\Core\User\Provider\FacebookProvider;
use Polcode\CasperBundle\Security\Core\User\Provider\ExternalProviderInterface;

class FOSUBUserProvider extends BaseClass {

    /**
     * {@inheritDoc}
     * TODO: refactor it using externalUserProvider
     */
    public function connect(UserInterface $user, UserResponseInterface $response) {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $externalUserProvider = $this->getExternalUserProvider($response);
        $user = $externalUserProvider->getUser();

        if (null === $user) {
            $user = $externalUserProvider->createUser();
        } else {
            $externalUserProvider->updateUserLoginInfo($user);
        }

        return $user;
    }

    /**
     * 
     * @param UserResponseInterface $response
     * @return ExternalProviderInterface
     * @throws \Exception
     */
    private function getExternalUserProvider(UserResponseInterface $response) {
        $serviceName = $response->getResourceOwner()->getName();
        switch (strtolower($serviceName)) {
            case 'facebook' : return new FacebookProvider($this->userManager, $response);
                break;
            default: throw new \Exception('Unknow external user provider: ' . $serviceName);
        }
    }

}
