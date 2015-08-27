<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Odiseo\Bundle\BrancaRallyBundle\Form\Type\UserType;
use Odiseo\Bundle\BrancaRallyBundle\Entity\User;
use Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;

class EmailTestController extends Controller
{
	public function sendEmailAction()
	{
		$tokenService = $this->container->get("app.services.token");
		//el email  debería ser el mismo al que se envía 
		$token = $tokenService->encodeToken("usuarioa@example.com");
		$view =  $this->render('OdiseoBrancaRallyBundle:Frontend/Mailer:mailTemplate.html.twig' , array('token' => $token) );
		$mailService = $this->container->get("app.services.mail");
		$failure = $mailService->sendMail("odiseo.team@gmail.com" , $view);
		
		return new Response('Email enviado');	
	}
	public function sendEmailWinnerAction($id)
	{
		$raceRepository = $this->get('odiseo.repository.race');
	
		$race = $raceRepository->findOneBy(array('id' => $id));
		$team = $race->getTeamWinner($race);
		($team==Team::TEAM1)?$teamView='Unico':$teamView='Branca';
		$message = 'Se han enviado los emails!';
		if($race->isEnded()){
			$email = 'juan@bassobrovelli.com';
			 
			$view =  $this->renderView('OdiseoBrancaRallyBundle:Backend/Mailer:ganador'.$teamView.'.html.twig' ,
					array(
							'race' => $race
	
					) )
					;
					$mailService = $this->container->get("app.services.mail");
					$failure = $mailService->sendMail($email , $view);
			
		}else{
			$message = 'No se han enviado los emails porque la carrera no termino';
		}
	
		return new Response($message);
	}
}
