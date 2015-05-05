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
     * 
     */
    public function indexAction()
    {
        $name = 'aa';
        return array('name' => $name);
    }
}
