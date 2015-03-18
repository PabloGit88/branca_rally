<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OdiseoBrancaRallyBundle:Default:index.html.twig');
    }
}
