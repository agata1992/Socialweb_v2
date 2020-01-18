<?php
	
namespace AppBundle\Service;	

use Symfony\Component\HttpFoundation\RequestStack;
	
class MailService{
	
	protected $requestStack;
	protected $mailer;
	
	public function __construct(RequestStack $requestStack,\Swift_Mailer $mailer)
	{
		$this->requestStack = $requestStack;
		$this->mailer = $mailer;
	}	
	
	public function mailScheme1($email,$name,$title,$body){
	
		$message  = \Swift_Message::newInstance()
						->setSubject($title)
						->setFrom(array('' => "Socialweb"))
						->setTo(array($email => $name))
						->setBody($body);
				
		$this->mailer->send($message);
	}	
}	
?>