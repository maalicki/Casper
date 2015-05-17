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
                throw \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
            }
        
            $allUsers     = $usersRepo->findAll();
            $invitedUsers = $this->getInvitedUsers($event);
            $joinedUsers  = $event->getJoinedUsers()->toArray();

            return $this->render('invitations/invitations.html.twig', array(
                'event'         => $event,
                'uninvitedUsers'=> array_diff($allUsers, $joinedUsers),
                'invitedUsers'  => $invitedUsers,
                'joinedUsers'   => $joinedUsers,
                'description'   => $this->getInvitationDescription($event)
            ));
        }
        
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    }
    
    public function handleFormAction(Request $request, Event $Event) {
        $em = $this->getDoctrine()->getManager();
        
        $inviteUsers = $request->request->get('inviteUsers');
        if (!empty($inviteUsers)) {

            $usersRepo = $em->getRepository('PolcodeCasperBundle:User');

            $invitation = $this->getOrCreateEventInvitation($Event, $em);
            $invitation->setSender($this->getUser());
            $invitation->setEvent($Event);
            $invitation->setDescription($request->request->get('description'));
            #$invitation->removeAllReceiver();

            foreach ($inviteUsers as $userIdToInvite) {
                $userToInvite = $usersRepo->find($userIdToInvite);
                $invitation->addReceiver($userToInvite);
                $this->sendEmailInvitation($Event, $userToInvite);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->set('success', 'E-mail with invites was send to selected members!');
        } else {
            $this->get('session')->getFlashBag()->set('danger', 'Your form was submitted but you need to select users to invite them!');
        }
        
        return $this->redirectToRoute('casper_eventMembers', array('id' => $Event->getId()));
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
    
    public function joinToEventAction($id) {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('PolcodeCasperBundle:Event')->find($id);
        $user = $this->getUser();
        $user->joinToEvent($event);
        $em->flush();
        return $this->redirectToRoute('casper_eventView', array('id' => $event->getId()));
    }
    
}