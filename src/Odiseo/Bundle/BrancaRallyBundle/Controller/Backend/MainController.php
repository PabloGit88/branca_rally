<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Odiseo\Bundle\BrancaRallyBundle\Form\Type\UserType;
use Odiseo\Bundle\BrancaRallyBundle\Entity\User;
use Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class MainController extends Controller
{
    public function dashboardAction(Request $request)
    {
    	$raceRepository = $this->get('odiseo.repository.race');
    	$races = $raceRepository->findAll();
    	
    	$stadistics = array();
    	
    	//Races stadistics
    	$racesStadistics = array();
    	$racesTotals = 0;
    	$team1Totals = 0;
    	$team2Totals = 0;
    	$soundsDurationTotals = 0;
    	$team1SoundsDurationTotals = 0;
    	$team2SoundsDurationTotals = 0;
    	
    	foreach ($races as $race)
    	{
    		$team1TotalsInRace = $race->countParticipationsOnTeam(Team::TEAM1);
    		$team2TotalsInRace = $race->countParticipationsOnTeam(Team::TEAM2);
    		$totalsInRace = $team1TotalsInRace+$team2TotalsInRace;   		
    		$team1SoundsDurationInRace = $race->countSoundsDurationOnTeam(Team::TEAM1);
    		$team2SoundsDurationInRace = $race->countSoundsDurationOnTeam(Team::TEAM2);
    		$totalsSoundsDurationInRace = $team1SoundsDurationInRace+$team2SoundsDurationInRace;
    		 
    		$raceStadistic = array(
    			'canShow' => $race->getIsActive() || $race->isEnded(),
    			'entity' => $race,
    			'teamParticipations' => array(
    				'totals' => $totalsInRace, 
    				Team::TEAM1 => $team1TotalsInRace,
    				Team::TEAM2 => $team2TotalsInRace
    			),
    			'teamSoundsDurations' => array(
    				'totals' => $totalsSoundsDurationInRace,
    				Team::TEAM1 => $team1SoundsDurationInRace,
    				Team::TEAM2 => $team2SoundsDurationInRace
    			)
    		);
    		
    		$racesStadistics[] = $raceStadistic;
    		
    		$team1Totals += $team1TotalsInRace;
    		$team2Totals += $team2TotalsInRace;
    		$racesTotals += $totalsInRace;
    		
    		$team1SoundsDurationTotals += $team1SoundsDurationInRace;
    		$team2SoundsDurationTotals += $team2SoundsDurationInRace;
    		$soundsDurationTotals += $totalsSoundsDurationInRace;
    	}
    	
    	$stadistics['races'] = $racesStadistics;
    	$stadistics['racesTotals'] = $racesTotals;
    	$stadistics['team1Totals'] = $team1Totals;
    	$stadistics['team2Totals'] = $team2Totals;
    	$stadistics['soundDurationTotals'] = $soundsDurationTotals;
    	$stadistics['team1SoundsDurationTotals'] = $team1SoundsDurationTotals;
    	$stadistics['team2SoundsDurationTotals'] = $team2SoundsDurationTotals;
    	    	
        return $this->render('OdiseoBrancaRallyBundle:Backend/Main:dashboard.html.twig', array(
        	'stadistics' => $stadistics
        ));
    }
    
    public function loadParticipationAction(Request $request)
    {   
    	$teamSelected = $request->query->get('equipo');
    	
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('OdiseoBrancaRallyBundle:Race');
    	$activeRace = $repository->findOneBy(array('isActive' => true));
    	
    	$form = $this->createForm(new UserType($teamSelected, $activeRace), null, array(
    		'action' => $this->generateUrl('odiseo_branca_rally_backend_load_participation').'?equipo='.$teamSelected
    	));
    	
    	if($request->isMethod('POST'))
    	{
    		$form->handleRequest($request);
    		 
    		if ($form->isValid())
    		{
    			$em = $this->getDoctrine()->getManager();
    			$formData = $form->getData();

    			// get user//
    			$name = $formData->getName();
    			$lastname = $formData->getLastName();
    			$phone = $formData->getPhone();
    			$dni = $formData->getDni();
    			$email = $formData->getEmail();

    			$repository = $this->get('odiseo.repository.user');
    			$User = $repository->findOneBy(array('dni' => $dni));
    			
    			//If user is null
				if ($User == null)
				{
	    			$User = new User();
	    			$User->setFullname($name.' '.$lastname);
	    			$User->setName($name);
	    			$User->setLastName($lastname);
	    			$User->setPhone($phone);
	    			$User->setDni($dni);
	    			$User->setEmail($email);
	    			$User->setUsername($dni);
	    			$User->setPassword('11111111111');
	    			$em->persist($User);
				}
				
    			$participating = false;
    			$participationForm = $formData->getParticipations();
    			$participations = $User->getParticipations();
    			foreach ($participations as $participation)
    			{
    				$raceForm = $participation->getRace()->getName();
    				$raceNameForm = $participationForm->getRace()->getName();
    				//si el usuario ya participo de la carrera
    				if ($raceForm == $raceNameForm)
    				{
    					$participating = true;
    				}
    			}

    			//If user is not participating on race    			
    			if (!$participating)
    			{
    				$participationForm->setUser($User);
    				
    				$errorMessage = null;
    				
    				try {
	    				//Save the sound file
	    				$soundFile = $participationForm->getSoundFile();
	    				$destFolder = $this->get('kernel')->getRootDir().'/../web/uploads/';
	    				
	    				$soundFilename = md5($User->getDni().$participationForm->getRace()->getName()).'.'.$soundFile->getClientOriginalExtension();
	    				$soundFile->move($destFolder, $soundFilename);
	    				$participationForm->setSoundFile($soundFilename);
	
	    				// convert sound file	
	    				$this->get('odiseo_branca_rally.media_converter')->convertAudio($destFolder.$soundFilename);

	    				//Get the audio duration
	    				$totalSeconds = $this->get('odiseo_branca_rally.media_converter')->getDuration($destFolder.$soundFilename);
	    				if(is_int($totalSeconds))
	    				{
	    					$participationForm->setSoundDuration($totalSeconds);
	    				}
	    				
	    				//Save the participation
	    				$em->persist($participationForm);
		    			$em->flush();
		    			
	    				// generate token and send email
	    				$tokenService = $this->container->get("app.services.token");
	    				$token = $tokenService->encodeToken($participationForm->getId());
	    				$view =  $this->renderView('OdiseoBrancaRallyBundle:Frontend/Mailer:userParticipation.html.twig' , 
	    						array(
	    								'token' => $token,
	    								'userParticipation' => $participationForm
	    								
	    						) )
	    				;
	    				$mailService = $this->container->get("app.services.mail");
	    				$failure = $mailService->sendMail($email , $view);	    				
	    				
    				}catch(\Exception $e)
    				{
    					throw $e;
    					$errorMessage = $e->getMessage();
    				}
    				
    				if($errorMessage)
    				{
    					$this->get('session')->getFlashBag()->add('error', 'Se produjo un error: '.$errorMessage);
    				}else 
    				{
	    				$this->get('session')->getFlashBag()->add('notice', 'Usuario guardado correctamente.');
	    				return $this->redirect($this->generateUrl('odiseo_branca_rally_backend_load_participation').'?equipo='.$teamSelected);    					
    				}
	    			
    			}else{
    				$this->get('session')->getFlashBag()->add('error', "El usuario ya ha participado en la carrera.");
    			}    			
    		}
    	}	 

        return $this->render('OdiseoBrancaRallyBundle:Backend/Main:load_participation.html.twig', array(
    		'form' => $form->createView(),
        	'teamSelected' => Team::getTeamName($teamSelected),
        	'activeRace' => $activeRace
    	));
    }
    
    public function participationFilterFormAction(Request $request)
    {
    	$dbRaces = $this->get('odiseo.repository.race')->findAll();
    	
    	$races = array(
    		'-1' => 'Todas las carreras'		
    	);
    	foreach ($dbRaces as $dbRace)
    	{
    		$races[$dbRace->getId()] = $dbRace->getName();
    	}
    	
    	$teams = array(
    		'-1' => 'Todos los equipos',
    		Team::TEAM1 => Team::getTeamName(Team::TEAM1),
    		Team::TEAM2 => Team::getTeamName(Team::TEAM2)
    	);
    	
    	return $this->render('OdiseoBrancaRallyBundle:Backend/Filter:participationFilterForm.html.twig', array(
    		'races' => $races,
    		'teams' => $teams,
    		'request' => $request
    	));
    }
    
    public function searchParticipationsAction(Request $request)
    {
    	$entity = $this->get('easyadmin.configurator')->getEntityConfiguration('UserRaceParticipation');
    	$fields = $entity['list']['fields'];
    	$notCleanedSearchFields = $request->query->has('search')?$request->query->get('search'):array();
    	$searchFields = array();
    	foreach ($notCleanedSearchFields as $searchField => $searchFieldValue)
    	{
    		if($searchFieldValue != '-1')
    		{
    			$searchFields[$searchField] = $searchFieldValue;
    		}
    	}
    	
    	$paginator = $this->findParticipationsBy($searchFields, $request->query->get('page', 1), 100);

    	
    	return $this->render('@EasyAdmin/list.html.twig', array(
    		'paginator' => $paginator,
    		'fields'    => $fields,
    	));
    }
    
    protected function findParticipationsBy($searchFields, $page = 1, $maxPerPage = 15)
    {
    	$query = $this->getDoctrine()->getManager()->createQueryBuilder()
    	->select('p, r')
    	->from('Odiseo\Bundle\BrancaRallyBundle\Entity\UserRaceParticipation', 'p')
    	->leftJoin('p.race', 'r')
    	;
    
    	$i = 1;
    	foreach ($searchFields as $field => $value) 
    	{
    		$wildcards = $this->getDoctrine()->getConnection()->getDatabasePlatform()->getWildcards();
    		$value = addcslashes($value, implode('', $wildcards));
    		if($field == 'race')
    		{
    			$query->andWhere("r.id LIKE :query".$i)->setParameter('query'.$i, '%'.$value.'%');
    		}else
    		{
    			$query->andWhere("p.$field LIKE :query".$i)->setParameter('query'.$i, '%'.$value.'%');	
    		}
    		$i++;
    	}
    
    	$paginator = new Pagerfanta(new DoctrineORMAdapter($query, false));
    	$paginator->setMaxPerPage($maxPerPage);
    	$paginator->setCurrentPage($page);
    
    	return $paginator;
    }
    
    public function downloadExcelAction(Request $request)
    {

    	$entity = $this->get('easyadmin.configurator')->getEntityConfiguration('UserRaceParticipation');
    	$fields = $entity['list']['fields'];
    	$notCleanedSearchFields = $request->query->has('search')?$request->query->get('search'):array();
    	$searchFields = array();
    	foreach ($notCleanedSearchFields as $searchField => $searchFieldValue)
    	{
    		if($searchFieldValue != '-1')
    		{
    			$searchFields[$searchField] = $searchFieldValue;
    		}
    	}
    	
    	$paginator = $this->findParticipationsBy($searchFields, $request->query->get('page', 1), 99999999);
    	
    	return $this->render('OdiseoBrancaRallyBundle:Backend/Main:downloadExcel.html.twig', array(
    		'paginator' => $paginator,
    		'fields'    => $fields,
    	));
    }
    
    public function sendEmailViewAction($id)
    {
    	$raceRepository = $this->get('odiseo.repository.race');
    	
    	$race = $raceRepository->findOneBy(array('id' => $id));
    	
    	$message = 'Se han enviado los emails!';

    	if($race->isEnded()){
	    	$participations = $this->get('odiseo.repository.participation')->createQueryBuilder('p')
	    	->where('p.race = :race')
	    	->setParameter('race', $race)
	    	->getQuery()
	    	->execute();
	    	
	    	foreach ($participations as $participation)
	    	{
	    		$email = $participation->getUser()->getEmail();
	    		
		    	$view =  $this->renderView('OdiseoBrancaRallyBundle:Backend/Mailer:viewRace.html.twig' , 
		    		array(
		    				'race' => $race
		    								
		    				) )
		    	;
		    	$mailService = $this->container->get("app.services.mail");
		    	$failure = $mailService->sendMail($email , $view);
	    	}	
    	}else{
    		$message = 'No se han enviado los emails. La carrera aun no ha finalizado';
    	}

    	return new JsonResponse(array('message' => $message));
    }
    
    public function sendEmailWinnerAction($id)
    {
    	$raceRepository = $this->get('odiseo.repository.race');
    	 
    	$race = $raceRepository->findOneBy(array('id' => $id));
    	$team = $race->getTeamWinner($race); 
    	($team==Team::TEAM1)?$teamView='Unico':$teamView='Branca';
    	$message = 'Se han enviado los emails!';
    	if($race->isEnded()){
    	
	    	$participations = $this->get('odiseo.repository.participation')->createQueryBuilder('p')
	    	->where('p.race = :race')
	    	->setParameter('race', $race)
	    	->getQuery()
	    	->execute();
	    	 
	    	foreach ($participations as $participation)
	    	{
	    		$email = $participation->getUser()->getEmail();
	    
	    		$view =  $this->renderView('OdiseoBrancaRallyBundle:Backend/Mailer:ganador'.$teamView.'.html.twig' ,
	    				array(
	    						'race' => $race
	    	    		
	    				) )
	    		;
	    		$mailService = $this->container->get("app.services.mail");
	    		$failure = $mailService->sendMail($email , $view);
	    	}	
    	}else{
    		$message = 'No se han enviado los emails. La carrera aun no ha finalizado';
    	}
    	 
    	return new JsonResponse(array('message' => $message));
    }
}
