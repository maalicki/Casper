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
            $IP = [ /* Defaults Poland's geolocation */
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

        if($Request->isMethod('POST') && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($Event);
            $em->flush();

            return $this->redirect($this->generateUrl('casper_newEvent'));
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
        
        $Event = new Event();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('PolcodeCasperBundle:Event');

        $Event = $repository->findOneById($id);
        
        if($id && $Event) {
            
            $user = $this->container->get('security.context')->getToken()->getUser();

            $isOwner = false;

            $form = $this->createForm(new EventFormType() , $Event, array('disabled' => true));
            
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

            $marker->setOptions(array(
                'clickable' => false,
                'flat'      => true,
                'id'        => $Event->getId()
            ));

            $map->addMarker($marker);

            return $this->render('events/event.html.twig', array(
                'form'      => isset($form) ? $form->createView() : NULL,
                'event'     => $Event,
                'map'       => $map,
                'mapjs'     => $mapjs,
            ));   
        }
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    }
    
    public function getNearestEventsAction(Request $Request) {
        
        if( $Request->isXmlHttpRequest() ) {
            
            $post = json_decode($Request->getContent(), true);
            
            $latitude   =   $Request->request->get('latitude');
            $longitude  =   $Request->request->get('longitude');
            
            #$center = $post['center'];
            #$radius = $post['radius'];
            
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('PolcodeCasperBundle:Event');
            
            $params = [
              'latitude'    =>    $latitude,
              'longitude'   =>    $longitude,
              'distance'    =>    5,
            ];
            
            $events = $repository->findTheClosest( $params );
            
            $baseurl = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath();
            
            /* check if user is logged, if true change google map markers */
            if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ) {
                
                $user = $this->container->get('security.context')->getToken()->getUser();
                $user = $user->getId();
                
                foreach( $events as &$event ) {
                    $event['test'] = $user;
                    $event['marker'] = $baseurl.'/img/flag2.png';
                    
                    if( $event['user'] == $user )
                        $event['marker'] = $baseurl.'/img/flag1.png';
                }
            }
            
            $response = new \Symfony\Component\HttpFoundation\JsonResponse();
            $response->setData([ 'events' => $events ]);
            return $response;
        }
        
        return $this->renderText('No results.');
    }
    
    public function deleteEventAction($id) {
        
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && $id ) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('PolcodeCasperBundle:Event');

            $event = new Event();
            $event = $repository->findOneById($id);
            
            if( $event->getUser() === $user ) {

                $event->setDeleted(1);
                $em->flush();
                return $this->redirect($this->generateUrl('casper_userMyEvents'));
            }
        }
        throw Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
        
    }
    
}
