<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use Polcode\CasperBundle\Entity\User;


class UserController extends Controller {
    
    public function myEventsAction(Request $Request) {
        
        return $this->render('user/myEvents.html.twig', array(
            
        ));
    }
}
