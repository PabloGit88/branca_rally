<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Test;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TokenTestController extends Controller
{	
	public function testAction($token)
	{
		$tokenService = $this->container->get("app.services.token");
    	$userParticipationId = $tokenService->decodeToken($token);

    	ldd($userParticipationId);
	}
}
