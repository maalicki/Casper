<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Entity\EventRepository;
use Polcode\CasperBundle\Entity\Event;
use Polcode\CasperBundle\Entity\User;
use Polcode\CasperBundle\Entity\Invitation;


class InvitationController extends Controller {
    
    public function indexAction(Request $request, $eventId) {
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('PolcodeCasperBundle:User');
        $eventRepo = $em->getRepository('PolcodeCasperBundle:Event');
        
        $event = $eventRepo->find($eventId);
        if ($event->getUser()->getId() !== $this->getUser()->getId()) {
            throw new \Symfony\Component\Security\Acl\Exception\Exception('Not permitted');
        }
        
        $uninvitedUsers = $usersRepo->findAll();
        $invitedUsers = $this->getInvitedUsers($event);
        return $this->render('invitation/send.html.twig', array(
                    'uninvitedUsers' => array_diff($uninvitedUsers, $invitedUsers),
                    'invitedUsers' => $invitedUsers,
                    'description' => $this->getDescriptionOfInvitation($event)
        ));
    }
    
}
