<?php

namespace Polcode\CasperBundle\Utils;
use Polcode\CasperBundle\Entity\Event;
use Polcode\CasperBundle\Entity\User;
use Polcode\CasperBundle\Entity\Invitations;

/**
 * @author Lukasz Malicki
 */
class InvitationsManager {
    
    /**
     * @param User|null $User
     * @param Event $Event
     * @return boolean
     */
    public function getOrCreateEventInvitation(Event $Event, $em) {
        $invitation = $Event->getInvitation();
        if ($invitation === null) {
            $invitation = new Invitations();
            $Event->setInvitation($invitation);
            $em->persist($invitation);
        }
        return $invitation;
    }
    
    /**
     * @param User|null $User
     * @param Event $Event
     * @return boolean
     */
    public function isUserInvitedToEvent(User $User, Event $Event) {
        $invitation = $Event->getInvitation();
        
        if ($invitation === null || $User === null) {
            return false;
        }
        
        if(in_array($User, $Event->getInvitation()->getReceivers()->toArray() )) {
            return true;
        }
        
        return false;
    }
    
}