<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Forms\EventFormType;
use Polcode\CasperBundle\Entity\Event;


class EventController extends Controller {

    public function newEventAction(Request $Request) {
        
        
        
        $Event = new Event();
        $Session = $this->get('session');
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $Event->setUserId( $user->getId() )
                ->setDeleted('0');
        
            $form = $this->createForm(new EventFormType() , $Event);

            $form->handleRequest($Request);

            if($Request->isMethod('POST')){
                if($form->isValid()){

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($Event);
                    $em->flush();

                }else{
                    //$Session->getFlashBag()->add('danger', 'Popraw błędy formularza.');
                    #$this->get('edu_notification')->addError('Popraw błędy formularza.');
                }
            }
        
        
        return $this->render('events/event.html.twig', array(
            'form' => isset($form) ? $form->createView() : NULL,
            'map'  => $this->get('ivory_google_map.map'),
            'ltd'  => '52.281601868071434',
            'lgt'  => '18.852882385253906'
        ));
        
    }
}
