<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use Polcode\CasperBundle\Entity\User;


class UserController extends Controller {
    
    public function myEventsAction(Request $Request) {
        
        $userId = $this->container->get('security.context')->getToken()->getUser()->getId();
        
        $repository = $this->getDoctrine()->getRepository('PolcodeCasperBundle:Event');
        
        $events = $repository->findBy([
            'userId' => $userId,
            'deleted'=> 0
        ]);
        
        
        $product = $this->getDoctrine()
            ->getRepository('PolcodeCasperBundle:User')
            ->find($userId);
        
        return $this->render('user/myEvents.html.twig', array(
            'events'    => $events
        ));
    }
}
