<?php

namespace Polcode\CasperBundle\Security\Core\User\Provider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of FacebookProvider
 *
 * @author dkociuba
 */
class FacebookProvider implements ExternalProviderInterface {

    /**
     * @var UserResponseInterface 
     */
    private $response;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager, UserResponseInterface $userResponseInterface) {
        $this->userManager = $userManager;
        $this->response = $userResponseInterface;
        $this->checkService();
    }

    private function checkService() {
        $serviceName = $this->response->getResourceOwner()->getName();
        if (strtolower($serviceName) !== 'facebook') {
            throw new \Exception('Facebook user provider expected response from facebook, but given ' . $serviceName);
        }
    }

    /**
     * 
     * @return User | null
     */
    public function getUser() {
        $facebookUserId = $this->response->getUsername();
        $user = $this->userManager->findUserBy(array('facebook_id' => $facebookUserId));
        return $user;
    }

    public function createUser() {
        $user = $this->userManager->createUser();
        $user->setFacebookId($this->response->getUsername());
        $user->setFacebookAccessToken($this->response->getAccessToken());
        //I have set all requested data with the user's username
        //modify here with relevant data
        $user->setUsername($this->response->getResponse()['name']);
        $user->setEmail($this->response->getEmail());
        $user->setPlainPassword('');
        $user->setPassword('*'); //uncorrect hash protects against login by login and password
        $user->setEnabled(true);
        $user->setSex($this->response->getResponse()['gender'] === 'male' ? 1 : 0);
        $user->setBirthDate(new \DateTime($this->response->getResponse()['birthday']));
        $this->userManager->updateUser($user);
        return $user;
    }

    public function updateUserLoginInfo(UserInterface $user) {
        //update access token
        $user->setFacebookAccessToken($this->response->getAccessToken());
    }

}
