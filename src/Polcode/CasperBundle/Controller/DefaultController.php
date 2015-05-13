<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Events\Event;

class DefaultController extends Controller {

    public function indexAction() {

        /**
         * get user IP and coordinates
         */
        $geocoder = new \Geocoder\ProviderAggregator();
        $adapter  = new \Ivory\HttpAdapter\CurlHttpAdapter();

        $chain = new \Geocoder\Provider\Chain([
            new \Geocoder\Provider\FreeGeoIp($adapter),
        ]);

        $geocoder->registerProvider($chain);
        
        $ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
        
        
        
        if( filter_var($ip, FILTER_VALIDATE_IP) && $ip != '127.0.0.1' && !preg_match('/^192.168.\d{1,3}\.\d{1,3}\z/', $ip) ) {
            $geocode = $geocoder->geocode( $ip )->first();
            $ltd = $geocode->getLatitude();
            $lgt = $geocode->getLongitude();
        } else {
            /* Poland's geolocation */
            $ltd = '51.267';
            $lgt = '20.017';            
        }
        
        /**
         * set map parameters
         */
        
        $map = new Map();
        
        $map->setAutoZoom(false);
        $map->setCenter( $ltd , $lgt, true);
        $map->setMapOption('zoom', 6);
        
        
        for( $i=0; $i<=1000; $i++ ) {
            
            /**
            * adding markers to the map
            */
            $marker = new Marker();
            // Configure your marker options
            $marker->setPrefixJavascriptVariable('marker_');
            $marker->setPosition( rand(49,55), rand(15,23), true);
            #$marker->setPosition(51, 20, true);

            $marker->setOptions(array(
                'clickable' => true,
                'flat'      => true,
                'id'        => $i
            ));
            
            $baseurl = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath();
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
            'map'  => $map
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
