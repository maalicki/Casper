<?php

namespace Polcode\CasperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Polcode\CasperBundle\Forms\UserType;
use Polcode\CasperBundle\Entity\User;

/**
 * @Route("/")
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="casper_index")
     * @Template;
     */
    public function indexAction() {
        $name = 'xx';

        $Session = $this->get('session');
        $Session->remove('registered');
        
        return array('name' => $name);
    }

    /**
     * @Route( "/register2", name="casper_registerUser")
     * @Template;
     */
    public function registerUserAction(Request $Request) {

        /*
         * Nick - text
         * Email - text (email)
         * Sex - radio collection
         * Birthdate - select
         * Rules - checkbox
         * Save - button
         */

        $Register = new User();
        
        /*
            $Register
                ->setEmail('asdasdasdasd@dasdasdasda.pl');
         
         */
        $Session = $this->get('session');

        if (!$Session->has('registered')) {

            $form = $this->createForm(new UserType(), $Register);

            $form->handleRequest($Request);

            if ($Request->isMethod('POST')) {
                
                if ($form->isValid()) {
                    

                    #$savePath = $this->get('kernel')->getRootDir() . '/../web/uploads/';
                    #$Register->save($savePath);

                    #$em = $this->getDoctrine()->getManager();
                    #$em->persist($Register);
                    #$em->flush();
                    
                    #$Session->set('registered', true);
                    
                    $Session->getFlashBag()->set('success', 'You heve been registered. You will now be redirected to login page.');
                    #return $this->redirect($this->generateUrl('casper_registerUser'));
                    
                    $response = new Redirect($this->router->generate('team_homepage'));
        
                } else {
                    $Session->getFlashBag()->set('danger', 'Please correct the form.');
                }
            }
        }


        return array(
            'form' => isset($form) ? $form->createView() : NULL,
        );
    }

    /**
     * @Route( "/test/{name}", defaults={"name" = 1},  name="casper_test")
     * @Template;
     */
    public function testAction($name) {
        return array('name' => $name);
    }
    
    /**
     * @Route(
     *  "/login",
     *  name = "casper_login"
     * )
     * @Template()
     */
    public function loginAction() {
        return [];
    }

}
