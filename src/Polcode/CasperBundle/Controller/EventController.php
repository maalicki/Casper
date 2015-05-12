<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Forms\EventFormType;
use Polcode\CasperBundle\Entity\Event;
use Symfony\Component\Form\FormError;

class EventController extends Controller {

    public function newEventAction(Request $Request) {
        
        $Event = new Event();
        $Session = $this->get('session');
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $Event->setUserId( $user )
                ->setDeleted('0');
        
            $form = $this->createForm(new EventFormType() , $Event);

            $form->handleRequest($Request);
            
            if($Request->isMethod('POST')){
                
                if($form->isValid()) {
                    if( $Event->isEventDatesValid() ) {
                        if( $Event->isEventSignUpValid() ) {
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($Event);
                            $em->flush();
                        } else {
                            $error = new FormError("Date ends after event end!");
                            $form->get('eventSignUpEndDate')->addError($error);
                            $Session->getFlashBag()->add('danger', 'Requested sign up date cannot be later than requested end date');                            
                        }
                    } else {
                        $error = new FormError("Event ends before it starts!");
                        $form->get('eventStop')->addError($error);
                        $Session->getFlashBag()->add('danger', 'Requested end date must be later than requested start date');
                    }
                }else{
                    $Session->getFlashBag()->add('danger', 'Popraw błędy formularza.');
                }
            }
        
        
        return $this->render('events/event.html.twig', array(
            'form' => isset($form) ? $form->createView() : NULL,
            'map'  => $this->get('ivory_google_map.map'),
            'ltd'  => '52.281601868071434',
            'lgt'  => '18.852882385253906'
        ));
        
    }
    
    public function getEventsAction(Request $Request) {
        
    }
}
