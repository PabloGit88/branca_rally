<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\Bundle\BrancaRallyBundle\Game\Team;
use Odiseo\Bundle\BrancaRallyBundle\Entity\User;
use Facebook\FacebookSession;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Odiseo\Bundle\BrancaRallyBundle\Entity\Race;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends Controller
{
    
    public function indexAction(Request $request)
    {    	
    	$raceRepository = $this->get('odiseo.repository.race');
    	$races = $raceRepository->findAll();
    	 
    	$race1 = null;
    	$race2 = null;
    	 
    	if(isset($races[0]))
    	{
    		$race1 = $races[0];
    	}
    	 
    	if(isset($races[1]))
    	{
    		$race2 = $races[1];
    	}
    	
   		return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:winners.html.twig', array(
   			'race1' => $race1,
   			'race2' => $race2
   		));
    }
    
    public function proximamenteAction(Request $request)
    {
    	 return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:winners.html.twig');
    	//return $this->redirect( "/proximamente/index.html");
    }   
    public function racesAndWinnersAction(Request $request)
    {
    	$raceRepository = $this->get('odiseo.repository.race');
    	$races = $raceRepository->findAll();
    	
    	$race1 = null;
    	$race2 = null;
    	
    	if(isset($races[0]))
    	{
    		$race1 = $races[0];
    	}
    	
    	if(isset($races[1]))
    	{
    		$race2 = $races[1];
    	}
    	
    	return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:racesAndWinners.html.twig', array(
    		'race1' => $race1,
    		'race2' => $race2
    	));
    }
       
    public function privateParticipationAction($token)
    {   
    	$tokenService = $this->container->get("app.services.token");
    	$userParticipationId = $tokenService->decodeToken($token);
    	
    	$userParticipation = $this->get('odiseo.repository.participation')->findOneBy(array('id' => $userParticipationId));
    	$playerParticipations = array($userParticipation);
    	
    	$team = $userParticipation->getTeam();
    	
    	//Facebook login
    	$facebook = $this->get('odiseo.facebook');
    	$facebook->setLoginRedirectUrl($this->generateUrl('odiseo_branca_rally_frontend_private_participation', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL));
    	
    	$user = $userParticipation->getUser();
    	$userFbAccessToken = $user->getFacebookAccessToken();
    	
    	try {
    		$session = $facebook->getFacebookSession($userFbAccessToken);
    	}catch (\Exception $e)
    	{
    		$session = null;
    	}
    	
    	$facebookLoginUrl = '#';
    	
    	if($session && $session->getAccessToken()->isValid())
    	{
    		$this->checkAndSaveUserFacebookSession($user, $session, $userFbAccessToken);
    		$friendsParticipations = $this->getUserFriendsParticipations($user, $session, $userParticipation->getRace(), $team);
    		
    		$playerParticipations = array_merge($playerParticipations, $friendsParticipations);    		
    	}else
    	{    		
    		try {
	    		$facebookLoginUrl = $facebook->getConfiguredLoginUrl();    			
    		} catch (\Exception $e) {
    			session_start();
    			$facebookLoginUrl = $facebook->getConfiguredLoginUrl();
    		}
    	}

    	$playerParticipations = array_merge($playerParticipations, $this->getParticipationsExcepts($playerParticipations, $userParticipation->getRace(), $team));
    	
    	$participationShareUrl = $this->generateUrl('odiseo_branca_rally_frontend_shared_participation', array('id' => $userParticipation->getId()), UrlGeneratorInterface::ABSOLUTE_URL);
    	$isShareUrl = $this->generateUrl('odiseo_branca_rally_frontend_shared_facebook', array('id' => $userParticipation->getId()), UrlGeneratorInterface::ABSOLUTE_URL);
    	
    	return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:privateParticipation.html.twig', array(
    		'userParticipation' => $userParticipation,
			'playerParticipations' => $playerParticipations,
    		'facebookLoginUrl' => $facebookLoginUrl,
    		'participationShareUrl' => $participationShareUrl,
    		'isShareUrl' => $isShareUrl,
    		'team' => ($team == Team::TEAM1)?'unico':'branca'
		));
    }
    
    public function sharedParticipationAction($id)
    {    	 
    	$userParticipation = $this->get('odiseo.repository.participation')->findOneBy(array('id' => $id));
    	
    	$team = $userParticipation->getTeam();
    	
    	$playerParticipations = array($userParticipation);
    	$randomParticipations = $this->getParticipationsExcepts($playerParticipations, $userParticipation->getRace(), $team, 20);
    	shuffle($randomParticipations);
    	$playerParticipations = array_merge($playerParticipations, $randomParticipations);
    	
    	return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:sharedParticipation.html.twig', array(
    		'userParticipation' => $userParticipation,
			'playerParticipations' => $playerParticipations,
    		'team' => ($team == Team::TEAM1)?'unico':'branca'
		));
    }
    
    private function checkAndSaveUserFacebookSession(User $user, FacebookSession $session, $userFbAccessToken)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$needFlush = false;
    	$fbUserId = $session->getSessionInfo()->asArray()['user_id'];
    	
    	if($fbUserId != $user->getFacebookUserId())
    	{
    		$user->setFacebookUserId($fbUserId);
    		$needFlush = true;
    	}
    	
    	if($session->getToken() != $userFbAccessToken)
    	{
    		$user->setFacebookAccessToken($session->getToken());
    		$needFlush = true;
    	}
    	
    	if($needFlush)
    		$em->flush();
    }
    
    private function getUserFriendsParticipations(User $user, FacebookSession $session, Race $race, $team)
    {
    	$facebook = $this->get('odiseo.facebook');
    	$facebookFriendsApi = $facebook->api('/'.$user->getFacebookUserId().'/friends', 'GET', null, $session);
    	
    	$facebookFriends = isset($facebookFriendsApi['data'])?$facebookFriendsApi['data']:array();
    	
    	$friendsIds = array();
    	foreach ($facebookFriends as $facebookFriend)
    	{
    		$friendsIds[] = $facebookFriend->id;
    	}
    	
    	$friendsParticipations = $this->get('odiseo.repository.participation')->createQueryBuilder('p')
    		->innerJoin('p.user','u')
    		->where("u.facebookUserId IN (:friendsIds)")
    		->andWhere('p.team = :team')
    		->andWhere('p.race = :race')
    		->setParameter('friendsIds', array_values($friendsIds))
    		->setParameter('team', $team)
    		->setParameter('race', $race)
    		->getQuery()
    		->execute();
    	
    	return $friendsParticipations;
    }
    
    private function getParticipationsExcepts($exceptParticipations, Race $race, $team, $limit = false)
    {
    	$exceptsIds = array();
    	
    	foreach ($exceptParticipations as $exceptParticipation)
    	{
			$exceptsIds[] = $exceptParticipation->getId();    		
    	}
    	
    	$participationsExceptsQuery = $this->get('odiseo.repository.participation')->createQueryBuilder('p')
    		->innerJoin('p.user','u')
    		->where("p.id NOT IN (:exceptsIds)")
    		->andWhere('p.team = :team')
    		->andWhere('p.race = :race')
    		->setParameter('exceptsIds', array_values($exceptsIds))
    		->setParameter('team', $team)
    		->setParameter('race', $race);
    	
    	if($limit)
    	{
    		$participationsExceptsQuery->setMaxResults($limit);
    	}
    	
    	$participationsExcepts = $participationsExceptsQuery->getQuery()->execute();
    	 
    	return $participationsExcepts;
    }
    
    public function viewRaceAction($id)
    {    	 
    	$raceRepository = $this->get('odiseo.repository.race');
    	$races = $raceRepository->findAll();
    	
    	$raceNumber = null;
    	
    	$race = $raceRepository->findOneBy(array('id' => $id));

    	if($race->isEnded()){
	    	//number of race
	    	if(isset($races[0]) and ($races[0]->getId() == $race->getId()))
	    	{
	    		$raceNumber = 1;
	    	}else
	    	{
	    		$raceNumber = 2;
	    	}
	    	
	    	$participationsTeam1 = $race->getParticipationsOnTeam(Team::TEAM1);
	    	$participationsTeam2 = $race->getParticipationsOnTeam(Team::TEAM2);
	    	
	    	return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:viewRace.html.twig', array(
				'participationsTeam1' => $participationsTeam1,
				'participationsTeam2' => $participationsTeam2,
				'raceNumber' => $raceNumber,
				'race' => $race
			));	
    	}else{
	    	return $this->redirect($this->generateUrl('odiseo_branca_rally_frontend_home'));	
    	}
    }
    
    public function sharedFacebookAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$userParticipation = $this->get('odiseo.repository.participation')->findOneBy(array('id' => $id));
    	 
    	$userParticipation->setIsFacebookShared(true);
    
    	$em->persist($userParticipation);
    	$em->flush();
    	 
    	return new JsonResponse(array('error' => false));
    }
    
    public function termsAction(Request $request)
    {
    	return $this->render('OdiseoBrancaRallyBundle:Frontend/Home:terms.html.twig', array(
    	));
    }
}
