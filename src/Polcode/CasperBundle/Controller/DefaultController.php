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
        
        if( filter_var($ip, FILTER_VALIDATE_IP) && $ip != '127.0.0.1' ) {
            $geocode = $geocoder->geocode( $ip )->first();
            $ltd = $geocode->getLatitude();
            $lgt = $geocode->getLongitude();
        } else {
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
            $marker->setIcon('http://blind-summit.co.uk/wp-content/plugins/google-map/images/marker_red.png');

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
            $map->getEventManager()->addEventOnce($event);
        }
        
        
        
        return $this->render('default/content.html.twig', array(
            
            'map'  => $map
        ));
    }


    public function testAction() {
        
        return $this->render('default/content.html.twig', array(
            
            'map'  => $this->get('ivory_google_map.map'),
            'ltd'  => '52.281601868071434',
            'lgt'  => '18.852882385253906'
        ));
        
    }
    
    public function loginAction() {
        
        
        return $this->render('default/login.html.twig', array(
            
        ));
    }

}
