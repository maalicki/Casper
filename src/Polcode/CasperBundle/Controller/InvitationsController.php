<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Entity\EventRepository;
use Polcode\CasperBundle\Entity\Event;
use Polcode\CasperBundle\Entity\User;
use Polcode\CasperBundle\Entity\Invitations;

use Polcode\CasperBundle\Forms\InviteFormType;

class InvitationsController extends Controller {
    
    public function indexAction(Request $request, $id) {
        
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('PolcodeCasperBundle:User');
        $eventRepo = $em->getRepository('PolcodeCasperBundle:Event');
        
        $event = $eventRepo->find($id);
        if( $event ) {
            if ($event->getUser()->getId() !== $this->getUser()->getId()) {
                throw Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
            }
        
            $uninvitedUsers = $usersRepo->findAll();
            $invitedUsers = $this->getInvitedUsers($event);

            return $this->render('invitations/invitations.html.twig', array(
                'event'         => $event,
                'uninvitedUsers'=> array_diff($uninvitedUsers, $invitedUsers),
                'invitedUsers'  => $invitedUsers,
                'description'   => $this->getInvitationDescription($event)
            ));
        }
        
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    }
    
    private function getInvitedUsers(Event $event) {
        $invitation = $event->getInvitation();
        if ($invitation === null) {
            return array();
        }
        return $event->getInvitation()->getReceivers()->toArray();
    }
    
    private function getInvitationDescription(Event $event) {
        $invitation = $event->getInvitation();
        if ($invitation === null) {
            return '';
        }
        return $event->getInvitation()->getDescription();
    }
    
    public function handleFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $inviteUsers = $request->request->get('inviteUsers');
        if (empty($inviteUsers)) {
            $inviteUsers = array();
        }
        
        $usersRepo = $em->getRepository('PolcodeCasperBundle:User');
        $eventRepo = $em->getRepository('PolcodeCasperBundle:Event');
        
        $event = $eventRepo->find($id);
        
        $invitation = $this->getOrCreateEventInvitation($event, $em);
        $invitation->setSender($this->getUser());
        $invitation->setEvent($event);
        $invitation->setDescription($request->request->get('description'));
        $invitation->removeAllReceiver();
        
        foreach ($inviteUsers as $userIdToInvite) {
            $userToInvite = $usersRepo->find($userIdToInvite);
            $invitation->addReceiver($userToInvite);
            $this->sendEmailInvitation($event, $userToInvite);
        }
        $em->flush();
        return $this->redirectToRoute('casper_eventMembers', array('id' => $id));
    }
    
    private function getOrCreateEventInvitation(Event $event, $em) {
        $invitation = $event->getInvitation();
        if ($invitation === null) {
            $invitation = new Invitations();
            $event->setInvitation($invitation);
            $em->persist($invitation);
        }
        return $invitation;
    }
    

    private function sendEmailInvitation(Event $event, User $user) {
        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
                ->setSubject('Invitation to event')
                ->setFrom('lukasz.malicki@polcode.net')
                ->setTo('lukasz.malicki@polcode.net')
                #->setTo($user->getEmail())
                ->setBody(
                        $this->renderView(
                            'emails/invitation.html.twig', array(
                            'event' => $event,
                            'path' => $this->generateUrl('casper_eventView', array('id' => $event->getId())))
                        ), 'text/html')
                ->addPart(
                $this->renderView(
                        'emails/invitation.txt.twig', array(
                        'event' => $event,
                        'path' => $this->generateUrl('casper_eventView', array('id' => $event->getId())))
                ), 'text/plain');
        $mailer->send($message);
    }
    
}