<?php

namespace Polcode\CasperBundle\Utils;
use Polcode\CasperBundle\Entity\Event;
use Polcode\CasperBundle\Entity\User;

/**
 * @author Lukasz Malicki
 */
class EventManager {
    
    /**
     * @param User|null $User
     * @param Event $Event
     * @return boolean
     */
    public function isUserMemberOfEvent(User $User, Event $Event) {
        if ($User === null) {
            return false;
        }
        if(in_array($User, $Event->getJoinedUsers())) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param User|null $user
     * @param Event $Event
     * @return boolean
     */
    public function isUserOwnerOfEvent(User $User, Event $Event) {
        if ($User === null) {
            return false;
        }
        return $User === $Event->getUser();
    }
    /**
     * @param User|null $User
     * @param Event $Event
     * @return boolean
     */
    public function isUserAllowedToJoinEvent(User $User, Event $Event) {
        if( $Event->getPrivate()) {
            return $this->isUserInvitedToEvent($User, $Event);
        }
        else {
            return true;
        }
    }

    
}