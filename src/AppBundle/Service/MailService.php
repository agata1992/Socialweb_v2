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
	
	
public function activateMail($link,$email,$name){
	
	$msg = 'Dziękujemy za rejestracje. Aby aktywować konto otwórz link '.$link;
	
	$message  = \Swift_Message::newInstance()
					->setSubject('Potwierdzenie rejestracji')
					->setFrom(array('' => "test test"))
					->setTo(array($email => $name))
					->setBody($msg);
				
	$this->mailer->send($message);
	
	
}	
	
	
	
	
	
}	
	
	
	
	
	
	
	
	
	
?>