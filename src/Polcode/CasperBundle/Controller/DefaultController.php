<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Events\Event;

use Polcode\CasperBundle\Librarys\IpTool;

class DefaultController extends Controller {

    public function indexAction(Request $Request) {

        $ip = $Request->getClientIp();
        
        $geo = new IpTool();
        if( !$IP = $geo->getGeo($ip) ) {
            /* Defaults Poland's geolocation */
            $IP = [
              'ltd' => '51.267',
              'lgt' => '20.017'
            ];
        }

        /* active events from database */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT q FROM PolcodeCasperBundle:Event q WHERE q.private=false AND q.eventStop>=:now')
                ->setParameter('now', new \DateTime);
        
        $baseurl = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath();
           
        $user = $this->container->get('security.context')->getToken()->getUser();
        $securityContext = $this->container->get('security.context');
        $userId = null;
        if( $securityContext->isGranted('IS_AUTHENTICATED_FULLY') ){
            $userId = $securityContext->getToken()->getUser()->getId();
        }
        
        /**
         * set map parameters
         */
        $map = new Map();
        
        $map->setAutoZoom(false);
        $map->setCenter( $IP['ltd'] , $IP['lgt'], true);
        $map->setMapOption('zoom', 6);
        
        
        foreach( $query->getResult() as $row ) {
            
            /**
            * adding markers to the map
            */
            $marker = new Marker();
            // Configure your marker options
            $marker->setPrefixJavascriptVariable('marker_');
            $marker->setPosition( $row->getLatitude(), $row->getLongitude(), true);
            #$marker->setPosition(51, 20, true);

            $marker->setOptions(array(
                'clickable' => true,
                'flat'      => true,
                'id'        => $row->getId()
            ));
            
            if( $row->getUser()->getid() == $userId )
                $marker->setIcon($baseurl.'/img/flag1.png');
            else
                $marker->setIcon($baseurl.'/img/flag3.png');
            #$marker->setIcon( 'https://cdn0.iconfinder.com/data/icons/fatcow/32/location_pin.png' );

            $map->addMarker($marker);
            
            /**
             * add clickable events to the markers
             */
            $event = new Event();
            // Configure your event
            $event->setInstance( $marker->getJavascriptVariable() );
            $event->setEventName( 'click' );
            $event->setHandle( 'function(){ markerEventClick( this.id );}' );

            $event->setCapture(false);
            $map->getEventManager()->addEvent($event);
        }
        
        
        
        return $this->render('default/content.html.twig', array(
            'map'  => $map,
            'jsmap' => $map->getJavascriptVariable()
        ));
    }


    public function ajaxCallAction(Request $Request) {
        
        $id = $Request->request->get('id');
        
        if( $Request->isXmlHttpRequest() && !empty($id) ) {
            
            $signUpUntil = new \DateTime();
            if( rand(0,1) == 1 ) { $op = '+'; } else {$op = '-';}
            $days = rand(1,15);
            $signUpUntil->modify("$op $days day");
            $days = rand(1,22);
            $signUpUntil->modify("+$days hours");
            $days = rand(1,59);
            $signUpUntil->modify("+$days minutes");
            
            $datetime2 = new \DateTime();
            $interval = $datetime2->diff($signUpUntil);
            
            $form = [
                'eventId'    =>  $id,
                'eventName'     => "Event name goes here",
                'eventDescription'  => "Here will be some sample event description. You can put in that box anything you want to share! It's simple and easy to do!'",
                'eventLocation' => "Beskidzka 14, 40-749 Katowice, Poland",
                'eventStart'    => (new \DateTime())->format('Y-m-d H:i:s'),
                'eventEnd'      => (new \DateTime())->format('Y-m-d H:i:s'),
                'eventSignUp'   => $interval,
                'eventMaxGuests'=> rand(0,100),
                'eventGuestes'  => [ 'name1', 'name2', 'name3', 'name4', 'name5' ],
                'eventLatitude' => rand(0,100) . '.' . rand(100,1000),
                'eventLongitute'=> rand(0,100) . '.' . rand(100,1000),
            ];
          
            return $this->render('views/events/eventDetails.html.twig', $form );
        }
        
        return $this->renderText('No results.');
    }
    
    public function loginAction() {
        
        
        return $this->render('default/login.html.twig', array(
            
        ));
    }

}
