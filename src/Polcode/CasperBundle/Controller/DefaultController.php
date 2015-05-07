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


    public function testAction($name) {
        
        return $this->render('default/test.html.twig', array(
            'name' => $name
        ));
        
    }
    
    public function loginAction() {
        
        
        return $this->render('default/login.html.twig', array(
            
        ));
    }

}
