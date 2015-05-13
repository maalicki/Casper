<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use Polcode\CasperBundle\Entity\User;


class UserController extends Controller {
    
    public function myEventsAction(Request $Request) {
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $repository = $this->getDoctrine()->getRepository('PolcodeCasperBundle:Event');
        
        $events = $repository->findBy([
            'user' => $user,
            'deleted'=> 0
        ]);
        
        return $this->render('user/myEvents.html.twig', array(
            'events'    => $events
        ));
    }
}
