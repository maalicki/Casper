<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Forms\EventFormType;
use Polcode\CasperBundle\Entity\Event;
use Symfony\Component\Form\FormError;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Marker;

use Polcode\CasperBundle\Librarys\IpTool;

class EventController extends Controller {

    public function newEventAction(Request $Request) {
        
        $ip = $Request->getClientIp();
        
        $geo = new IpTool();
        if( !$IP = $geo->getGeo($ip) ) {
            /* Defaults Poland's geolocation */
            $IP = [
              'ltd' => '51.267',
              'lgt' => '20.017'
            ];
        }
        
        $Event = new Event();
        $Session = $this->get('session');
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $Event->setUser( $user )
              ->setDeleted('0');
        
            $form = $this->createForm(new EventFormType('create') , $Event);

            $form->handleRequest($Request);
            
            if($Request->isMethod('POST')){
                
                if($form->isValid()) {
                    if( $Event->isEventDatesValid() ) {
                        if( $Event->isEventSignUpValid() ) {
                            
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($Event);
                            $em->flush();
                            
                            return $this->redirect($this->generateUrl('casper_newEvent'));
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
                    $Session->getFlashBag()->add('danger', 'Invalid form values.');
                }
            }

        /* set map */
        $map = new Map();
        
        $map->setAutoZoom(false);
        $map->setCenter( $IP['ltd'] , $IP['lgt'], true);
        $map->setMapOption('zoom', 6);
        
        $mapjs = $map->getJavascriptVariable();
        
        $js = 'google.maps.event.addListener(%s, "click", function(event) {placeMarker(event.latLng, %s);})';
            $clickEvent = $this->get('ivory_google_map.event');
            $clickEvent->setInstance($map->getJavascriptVariable());
            $clickEvent->setEventName('click');
            $clickEvent->setHandle(sprintf($js, $mapjs, $mapjs));
        
        $map->getEventManager()->addEvent($clickEvent);
        
        return $this->render('events/event.html.twig', array(
            'form' => isset($form) ? $form->createView() : NULL,
            'map'  => $map,
            'mapjs' => $mapjs,
        ));
        
    }
    
    public function viewAction($id = null) {
        
        if($id) {
            
            $Event = new Event();
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('PolcodeCasperBundle:Event');

            $Event = $repository->findOneById($id);
            
            if( $Event ) {
                $form = $this->createForm(new EventFormType('edit') , $Event, array('disabled' => true));

                    $map = new Map();
                    
                    $map->setAutoZoom(false);
                    $map->setCenter( $Event->getLatitude() , $Event->getLongitude(), true);
                    $map->setMapOption('zoom', 6);
                    $mapjs = $map->getJavascriptVariable();
                    
                    $js = 'google.maps.event.addListener(%s, "click", function(event) {})';
                        $clickEvent = $this->get('ivory_google_map.event');
                        $clickEvent->setInstance($map->getJavascriptVariable());
                        $clickEvent->setEventName('click');
                        $clickEvent->setHandle(sprintf($js, $mapjs, $mapjs));
                    
                    $map->getEventManager()->addEvent($clickEvent);
                    
                    /* add marker to the map */
                    $marker = new Marker();
                    // Configure your marker options
                    $marker->setPrefixJavascriptVariable('marker_');
                    $marker->setPosition( $Event->getLatitude(), $Event->getLongitude(), true);
                    #$marker->setPosition(51, 20, true);

                    $marker->setOptions(array(
                        'clickable' => false,
                        'flat'      => true,
                        'id'        => $Event->getId()
                    ));

                $map->addMarker($marker);
                
                return $this->render('events/event.html.twig', array(
                    'form' => isset($form) ? $form->createView() : NULL,
                    'map'  => $map,
                    'mapjs' => $mapjs,
                ));   
            }
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }
        
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        
    }
    
    public function getNearestEventsAction(Request $Request) {
        
        if( $Request->isXmlHttpRequest() ) {
            
            $post = json_decode($Request->getContent(), true);
            
            $latitude = filter_var($post['latitude'], FILTER_VALIDATE_FLOAT);
            $longitude = filter_var($post['longitude'], FILTER_VALIDATE_FLOAT);
            
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('PolcodeCasperBundle:Event');
            
            $events = $repository->findTheClosest($latitude, $longitude, 375);
            
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData([ 'events' => $events ]);
            return $response;
        }
        
        return $this->renderText('No results.');
    }
}
