<?php

namespace Polcode\CasperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/casper")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template;
     */
    public function indexAction()
    {
        $name = 'xx';
        
        return array('name' => $name);
    }
    
    /**
     * @Route( "/test/{name}", defaults={"name" = 1})
     * @Template;
     */
    public function testAction($name)
    {
        return array('name' => $name);
    }
}
