<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class EventController extends Controller {

    public function newEventAction() {
        
        return $this->render('events/nevEvent.html.twig', array(
            
        ));
        
    }
}
