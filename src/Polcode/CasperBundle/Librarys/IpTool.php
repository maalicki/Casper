<?php

namespace Polcode\CasperBundle\Librarys;

Class IpTool {
    
    public function getGeo($ip) {
        /**
         * get user IP and coordinates
         */
        $geocoder = new \Geocoder\ProviderAggregator();
        $adapter  = new \Ivory\HttpAdapter\CurlHttpAdapter();

        $chain = new \Geocoder\Provider\Chain([
            new \Geocoder\Provider\FreeGeoIp($adapter),
        ]);

        $geocoder->registerProvider($chain);
        
        
        if( filter_var($ip, FILTER_VALIDATE_IP) && $ip != '127.0.0.1' && !preg_match('/^192.168.\d{1,3}\.\d{1,3}\z/', $ip) ) {
            $geocode = $geocoder->geocode( $ip )->first();
            return [
              'ltd' => $geocode->getLatitude(),
              'lgt' => $geocode->getLongitude()
            ];
        }
            return false;
    }
}