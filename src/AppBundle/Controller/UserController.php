<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;
use AppBundle\Service\AdditionalService;

class UserController extends Controller{
	
	
	/**
	 * @Route("/user/{id}",name="path_user_home")
	 */
	
	public function homeAction(DBService $db_service,CookieService $cookie_service,$id){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $db_service->getUserData($user,0);
		
		$userData = $db_service->getUserData($id,1);
		
		$posts = $db_service->getposts($id);
		
		return $this->render('Profile/home.html.twig',array(
			'userData' => $userData,'myData' => $myData,
			'posts' => $posts
		));
		
	}
	
	
	/**
	 * @Route("/user/omnie/{id}",name="path_user_about")
	 */
	
	public function aboutAction(DBService $db_service,CookieService $cookie_service,$id){
	
	
	}
	
	/**
	 * @Route("/user/zdjecia/{id}",name="path_user_photo")
	 */
	
	public function photoAction(DBService $db_service,CookieService $cookie_service,$id){
	
	
	}
	
	/**
	 * @Route("/user/znajomi/{id}",name="path_user_friends")
	 */
	
	public function friendsAction(DBService $db_service,CookieService $cookie_service,$id){
	
	
	}
}
	
	
	
	
	
	
	
	
	
	
?>