<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;
use AppBundle\Service\AdditionalService;

use AppBundle\Entity\Posts\Posts;
use AppBundle\Entity\Posts\Subposts;

use AppBundle\Form\Posts\PostsForm;
use AppBundle\Form\Posts\SubpostsForm;

class UserController extends Controller{
	
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