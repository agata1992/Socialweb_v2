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

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints as Assert;


class LoginController extends Controller{
	
	/**
	 * @Route("/login",name="path_login")
	 */
	
	public function loginAction(Request $request,DBService $dbService,CookieService $cookieService){
	
		$user = $cookieService->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$user = new Login();
		$form = $this->createForm(LoginForm::class,$user);
					
		$form->handleRequest($request);
		
		if($form->isValid()){
		
			$email = $form["email"]->getData();
			$password = $form["password"]->getData();
				
			$result = $dbService->checkLoginCorrect($email,$password);
			
			if($result == 1){
			
				$cookieService->set_cookie($email);
				
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
	
	public function registerAction(Request $request,DBService $dbService,CookieService $cookieService,MailService $mailService){
		
		$user = $cookieService->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$register = new User();
		$form = $this->createForm(RegisterForm::class,$register);
					
		$form->handleRequest($request);
		
		if($form->isValid()){
			
			$additionalService = new AdditionalService();
			
			$password = $form['password']->getData();
			$password2 = $form['password2']->getData();
			
			if(!$additionalService->_s_has_upper_letters($password) ||
				!$additionalService->_s_has_lower_letters($password) ||
				!$additionalService->_s_has_numbers($password) ||
				!$additionalService->_s_has_special_chars($password)
			)
				$form->get('password')->addError(new FormError('Hasło musi zawierać dużą litere,małą,liczbę oraz znak specjalny'));
			
			if($password != $password2)
				$form->get('password2')->addError(new FormError('Hasła musz być identyczne'));

			$email = $form['email']->getData();
			
			$user_data = $dbService->getUserData($email,0);
			
			if($user_data != null)
				$form->get('email')->addError(new FormError('Email jest już w użyciu'));
			
			if($form->isValid()){
				
				$link = $dbService->setNoActivateAccount($register);
				
				$link = 'http://socialweb.ddns.net/aktywacja/'.$link;
				
				$name = $form['name']->getData().' '.$form['surname']->getData();
				
				$mailTitle = 'Potwierdzenie rejestracji';
				$mailBody = 'Dziękujemy za rejestracje. Aby aktywować konto otwórz link '.$link;
				
				$mailService->mailScheme1($email,$name,$mailTitle,$mailBody);
				
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
	
	public function signOutAction(CookieService $cookieService){
		
		$cookieService->delete_user_cookie();
		
		return $this->redirect($this->generateUrl('path_login'));
	}
	
	/**
	 * @Route(path="/aktywacja/{userId}/{link}")
	 */
	
	public function activateAction(DBService $dbService,$userId,$link){
		
		$result = $dbService->activateAccount($userId,$link);
		
		$session = $this->get('session');
		
		if($result == 0){
			
			$message = 'Wystąpił błąd';
			$session->getFlashBag()->add('danger',$message);
		}
		else{
			
			$message = 'Konto zostało aktywowane. Możesz się zalogować';
			$session->getFlashBag()->add('success',$message);
		}
		return $this->redirect($this->generateUrl('path_login'));
	}
	
	/**
	 * @Route(path="/zmianahasla",name="path_change_password")
	 */
	
	public function changePasswordLinkAction(Request $request,DBService $dbService,CookieService $cookieService,MailService $mailService){
		
		$user = $cookieService->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$form = $this->createFormBuilder()
			->add('email',EmailType::class,array(
				'constraints' => array(
					new Assert\NotBlank(array('message' => 'Pole nie może być puste')),
					new Assert\Email(array('message' => 'Wpisz poprany email'))
				)
			))
			->add('submit', SubmitType::class,array(
				'label'=>'Wyślij',
				'attr'=>array('class'=>'btn-primary')
			))
			->getForm();

		$form->handleRequest($request);
			
		if($form->isValid()){
				
			$email = $form['email']->getData();
				
			$result = $dbService->getUserData($email);
				
			if($result == NULL || ($result != NULL && $result->getActivate() == 0))
				$form->get('email')->addError(new FormError('Konto nie istnieje'));
			else{
				
				$name = $result->getName().' '.$result->getSurname();
				$id = $result->getId();
					
				$link = $dbService->setChangePasswordLink($id);
					
				$link = 'http://localhost/socialweb_v2/web/app_dev.php/zmienhaslo/'.$link;
					
				$mailTitle = 'Zmiana hasła';
				$mailBody = 'Witaj '.$name.'. Aby zmienić hasło otwórz link '.$link;
				
				$mailService->mailScheme1($email,$name,$mailTitle,$mailBody);
					
				$session = $this->get('session');
				$message = 'Email z linkiem do zmiany hasła został wysłany na podany adres.';
				$session->getFlashBag()->add('success',$message);
				
				return $this->redirect($this->generateUrl('path_login'));
			}
		}
		
		return $this->render('Login/changepassword.html.twig',array('form' => $form->createView()));
	}
	
	
	/**
	 * @Route(path="/zmienhaslo/{userId}/{link}")
	 */
	public function changePasswordAction(Request $request,DBService $dbService,CookieService $cookieService,$userId,$link){
		
		$user = $cookieService->check_exist_user_cookie();
		
		if($user != '')
			return $this->redirect($this->generateUrl('path_home'));
		
		$result = $dbService->checkActivateLinkExists($userId,$link);
		
		$session = $this->get('session');
		
		if($result == 0){
			
			$message = 'Wystąpił błąd';
			$session->getFlashBag()->add('danger',$message);
			return $this->redirect($this->generateUrl('path_login'));
		}
		
		$form = $this->createFormBuilder()
			->add('password',PasswordType::class,array(
				'label' => 'Hasło',
				'constraints' => array(
					new Assert\NotBlank(array('message' => 'Pole nie może być puste')),
					new Assert\Length(array('min' => 8,'minMessage' => 'Hasło musi mieć przynajmniej 8 znaków'))
				)
			))
			->add('password2',PasswordType::class,array(
				'label' => 'Powtórz hasło',
				'constraints' => array(
					new Assert\NotBlank(array('message' => 'Pole nie może być puste')),
				)
			))
			->add('submit', SubmitType::class,array(
				'label'=>'Zmień hasło',
				'attr'=>array('class'=>'btn-primary')
			))
			->getForm();
			
		$form->handleRequest($request);	
		
		if($form->isValid()){
		
			$additionalService = new AdditionalService();
			
			$password = $form['password']->getData();
			$password2 = $form['password2']->getData();
			
			if(!$additionalService->_s_has_upper_letters($password) ||
				!$additionalService->_s_has_lower_letters($password) ||
				!$additionalService->_s_has_numbers($password) ||
				!$additionalService->_s_has_special_chars($password)
			)
				$form->get('password')->addError(new FormError('Hasło musi zawierać dużą litere,małą,liczbę oraz znak specjalny'));
			
			if($password != $password2)
				$form->get('password2')->addError(new FormError('Hasła musz być identyczne'));
			
			if($form->isValid()){
			
				$dbService->changePassword($userId,$password);
				
				$message = 'Hasło zostało zmienione.Możesz się zalogować.';
				$session->getFlashBag()->add('success',$message);
				
				return $this->redirect($this->generateUrl('path_login'));
			}
		}
			
		return $this->render('Login/changepassword.html.twig',array('form' => $form->createView()));
	}
}
?>