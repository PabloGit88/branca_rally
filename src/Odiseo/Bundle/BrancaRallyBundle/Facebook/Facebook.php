<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\FacebookPageTabHelper;
use Facebook\FacebookJavaScriptLoginHelper;

class Facebook
{	
	protected $appHost;
	protected $tabUrl;
	protected $loginScope;
	protected $loginRedirectUrl;
	
	public function __construct($appId, $appSecret, $appHost, $tabUrl, $loginScope = 'basic_info, email, user_birthday') 
	{
		$this->appHost = $appHost;
		$this->tabUrl = $tabUrl;
		$this->loginScope = $loginScope;
		
		$this->loginRedirectUrl = $this->appHost;
		
		FacebookSession::setDefaultApplication($appId, $appSecret);
	}
	
	public function getAppHost()
	{
		return $this->appHost;			
	}
	
	public function getTabUrl()
	{
		return $this->tabUrl;
	}
	
	public function setLoginRedirectUrl($redirectUrl)
	{
		$this->loginRedirectUrl = $redirectUrl;
	}
	
	public function getLoginRedirectUrl()
	{
		return $this->loginRedirectUrl;
	}
	
	public function getConfiguredLoginUrl()
	{
		$helper = new FacebookRedirectLoginHelper($this->getLoginRedirectUrl());
		return $helper->getLoginUrl($this->loginScope);
	}
	
	public function getUserId()
	{
		try {
			$session = $this->getFacebookSession();
			if($session)
			{
				return $session->getSessionInfo()->asArray()['user_id'];
			}
		} catch(\Exception $ex) {
			//echo $ex->getMessage();
		}	
		
		return null;
	}
	
	public function api($path, $method, $parameters = null, $session = null)
	{
		try {
			$session = $session?$session:$this->getFacebookSession();
			
			$request = new FacebookRequest($session, $method, $path, $parameters);
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			
			return $graphObject->asArray();
		} catch(FacebookRequestException $ex) {
			throw $ex;
		}
	}
	
	public function isOnPageTab()
	{
		$tabHelper = new FacebookPageTabHelper();
		
		return $tabHelper->getPageId();
	}
	
	public function getFacebookSession($accessToken = null)
	{
		$helper = new FacebookRedirectLoginHelper($this->getLoginRedirectUrl());
		$helperJavascript = new FacebookJavaScriptLoginHelper();
		
		$session = $accessToken?new FacebookSession($accessToken):null;
				
		try 
		{
			//Get session from redirect
			if(!$session || !$session->getAccessToken()->isValid())
			{
				$session = $helper->getSessionFromRedirect();
			}
			
			//Get session from stored access token in session
			if(!$session && isset($_SESSION['myfb_access_token']))
			{
				$session = new FacebookSession($_SESSION['myfb_access_token']);
			}
			
			//Get session from javascript
			if(!$session || !$session->getAccessToken()->isValid())
			{
				$session = $helperJavascript->getSession();
			}
		} catch(\Exception $ex) {				
			// When validation fails or other local issues
			throw $ex;
		}
		
		//If session is valid, store on the session
		if($session && $session->getAccessToken()->isValid())
		{
			$_SESSION['myfb_access_token'] = $session->getToken();
		}
		
		return $session;
	}
}