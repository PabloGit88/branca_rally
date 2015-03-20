<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends Controller
{
    public function dashboardAction()
    {
    	
        return $this->render('OdiseoBrancaRallyBundle:Backend/Main:dashboard.html.twig');
    }
}
