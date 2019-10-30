<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;

class PageController extends Controller{
	
	
	/**
	 * @Route("/",name="path_home")
	 */
	
	public function homeAction(DBService $db_service,CookieService $cookie_service){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$user_data = $db_service->getUserData($user);
	
		
		return $this->render('home.html.twig',array('user_data' => $user_data));
		
	}
	
	
	
}
	
	
	
	
	
	
	
	
	
	
?>