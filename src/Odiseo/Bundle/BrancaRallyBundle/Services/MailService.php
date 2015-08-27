<?php

namespace Odiseo\Bundle\BrancaRallyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class MailService
{
	private $message;
	private $container;
	private $emailName;
	private $emailSubject;
	private $emailFrom;
	
	public function __construct(Container $container, $emailFrom, $emailSubject , $emailName)
	{
		$this->message = null;
		$this->container = $container;
		$this->emailFrom = $emailFrom;
		$this->emailName = $emailName;
		$this->emailSubject = $emailSubject;
	}
	
	public function sendMail($emailTo, $view)
	{
		$message = $this->buildMessage($emailTo, $view);
		$failures = $this->send($message);
		return $failures;
	}
	
	protected function send($message)
	{
		$failures = array();
		$mailer = $this->container->get('mailer');
		try {
			$mailer->send($message, $failures);
		}catch(\Exception $e){}
		
		// manually flush the queue (because using spool)
		//$spool = $mailer->getTransport()->getSpool();
		//$transport = $this->container->get('swiftmailer.transport.real');
		//$spool->flushQueue($transport);
		
		return $failures;
	}
	
	private function buildMessage($emailTo, $view)
	{
		$mailer = $this->container->get('mailer');
		
		return $mailer->createMessage()
			->setSubject($this->emailSubject)
			->setFrom(array($this->emailFrom => $this->emailName))
			->setTo($emailTo)
			->setBody($view, 'text/html');
	}
}