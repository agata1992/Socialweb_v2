<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;
use AppBundle\Service\AdditionalService;
use AppBundle\Service\MailService;

use AppBundle\Entity\Login;
use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use AppBundle\Form\RegisterForm;

class LoginController extends Controller{
	
	/**
	 * @Route("/login",name="path_login")
	 */
	
	public function loginAction(Request $request,DBService $db_service,CookieService $cookie_service){
	
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$user = new Login();
		$form = $this->createForm(LoginForm::class,$user);
					
		$form->handleRequest($request);
		
		if($form->isValid()){
		
			$email = $form["email"]->getData();
			$password = $form["password"]->getData();
				
			$result = $db_service->checkLoginCorrect($email,$password);
				print $result;
			if($result == 1){
			
				$cookie_service->set_cookie($email);
				
				return $this->redirect($this->generateUrl('path_home'));
			}
			else if($result == 2){
				
				$form->get('password')->addError(new FormError('Konto nie jest aktywne'));
			}
			else{
					
					$form->get('email')->addError(new FormError(''));
					$form->get('password')->addError(new FormError('Nieprawidłowe dane'));
				}
		}
		
		return $this->render('Login/login.html.twig',array('form'=>$form->createView()));
	}
	
	/**
	 * @Route("/rejestracja",name="path_register")
	 */
	
	public function registerAction(Request $request,DBService $db_service,CookieService $cookie_service,MailService $mailService){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$register = new User();
		$form = $this->createForm(RegisterForm::class,$register);
					
		$form->handleRequest($request);
		
		if($form->isValid()){
			
			$additional_service = new AdditionalService();
			
			$password = $form['password']->getData();
			$password2 = $form['password2']->getData();
			
			if(!$additional_service->_s_has_upper_letters($password) ||
				!$additional_service->_s_has_lower_letters($password) ||
				!$additional_service->_s_has_numbers($password) ||
				!$additional_service->_s_has_special_chars($password)
			)
				$form->get('password')->addError(new FormError('Hasło musi zawierać dużą litere,małą,liczbę oraz znak specjalny'));
			
			if($password != $password2)
				$form->get('password2')->addError(new FormError('Hasła musz być identyczne'));

			$email = $form['email']->getData();
			
			$user_data = $db_service->getUserData($email);
			
			if($user_data != null)
				$form->get('email')->addError(new FormError('Email jest już w użyciu'));
			
			if($form->isValid()){
				
				$link = $db_service->setNoActivateAccount($register);
				
				$link = 'http://localhost/socialweb_v2/web/app_dev.php/aktywacja/'.$link;
				
				$name = $form['name']->getData().' '.$form['surname']->getData();
				
				$mailService->activateMail($link,$email,$name);
				
				$session = $this->get('session');
				$message = 'Dziękujemy za rejestracje!!! Wysłaliśmy na podany adres link aktywacyjny.';
				$session->getFlashBag()->add('success',$message);
				
				return $this->redirect($this->generateUrl('path_register'));
			}
		}
		
		return $this->render('Login/login.html.twig',array('form'=>$form->createView()));
	}
	
	/**
	 * @Route(path="/wyloguj",name="path_sign_out")
	 */
	
	public function signOutAction(CookieService $cookie_service){
		
		$cookie_service->delete_user_cookie();
		
		return $this->redirect($this->generateUrl('path_login'));
	}
}
?>