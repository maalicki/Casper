<?php

namespace Polcode\CasperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Polcode\CasperBundle\Forms\RegistrationFormType;
use Polcode\CasperBundle\Entity\User;


class DefaultController extends Controller {

    public function indexAction() {
        $name = 'xx';

        $Session = $this->get('session');
        $Session->remove('registered');
        
        return $this->render('default/index.html.twig', array(
            'name' => $name
        ));
    }

    public function registerUserAction(Request $Request) {

        /*
         * Username - text
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


            $form = $this->createForm(new RegistrationFormType(), $Register);
            
            /*
             * Due to 'Best Practices' I've moved this part of code to a view. 
                $form
                    ->add('save', 'submit', array(
                        'label' => 'Save',
                        'attr'  => ['class' => 'btn btn-default pull-right']
                    ));
            */
            
            $form->handleRequest($Request);

            if ($Request->isMethod('POST')) {
                
                if ( $form->isSubmitted() && $form->isValid() ) {
                    

                    #$savePath = $this->get('kernel')->getRootDir() . '/../web/uploads/';
                    #$Register->save($savePath);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($Register);
                    $em->flush();
                    
                    #$Session->set('registered', true);
                    
                    $Session->getFlashBag()->set('success', 'You heve been registered.');
                    #return $this->redirect($this->generateUrl('casper_registerUser'));
                    
                    return $this->redirect($this->generateUrl(
                        'casper_index'
                    ));
        
                } else {
                    $Session->getFlashBag()->set('danger', 'Please correct the form.');
                }
            }
        }

        return $this->render('default/registerUser.html.twig', array(
            'form' => isset($form) ? $form->createView() : NULL,
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
