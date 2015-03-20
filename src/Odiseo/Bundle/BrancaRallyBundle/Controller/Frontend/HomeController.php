<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    
    public function indexAction()
    {    	    	
        return $this->render('OdiseoBrancaRallyBundle:Frontend:index.html.twig');
    	
    }
    
}
