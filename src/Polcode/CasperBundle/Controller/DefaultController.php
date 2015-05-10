<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction() {
        $name = 'xx';

        $Session = $this->get('session');
        $Session->remove('registered');
        
        return $this->render('default/index.html.twig', array(
            'name' => $name
        ));
    }


    public function testAction() {
        
        return $this->render('full.html.twig', array(
            
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
