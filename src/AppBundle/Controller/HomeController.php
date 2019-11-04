<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Request;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;
use AppBundle\Service\AdditionalService;

use AppBundle\Entity\Posts\Posts;
use AppBundle\Entity\Posts\Subposts;

use AppBundle\Form\Posts\PostsForm;
use AppBundle\Form\Posts\SubpostsForm;

class HomeController extends Controller{
	
	/**
	 * @Route("/",name="path_home")
	 */
	
	public function homeAction(Request $request,DBService $db_service,CookieService $cookie_service){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $userData = $db_service->getUserData($user,0);
		$posts = $db_service->getPosts($myData->getId());
		
		$postForm = new Posts();
		$formPost = $this->createForm(PostsForm::class,$postForm);
		
		$formPost->handleRequest($request);
		
		if($formPost->isValid()){
			
			$db_service->addPost($myData->getId(),$postForm);
			
			return $this->redirect($this->generateUrl('path_home'));
		}
			
		$subposts = $db_service->getSubposts($posts);	
		
		$formSubposts = array();
		$subpostForm = array();
		
		foreach($posts as $post){
			$id = $post -> getId();
			$subpostForm[$id] = new Subposts();
			
			$formSubposts[$id] = $this->createForm(SubpostsForm::class,
				$subpostForm[$id],array('attr' => array('id' => $id))
			);
			$formSubposts[$id]->handleRequest($request);
			if($formSubposts[$id]->isValid()){
				
				$db_service->addSubpost($myData->getId(),$id,$subpostForm[$id]);
			
				return $this->redirect($this->generateUrl('path_home'));	
			}
			
			$formSubposts[$id] = $formSubposts[$id]->createview();
		}
		
		return $this->render('Profile/home.html.twig',array(
			'userData' => $userData,'myData' => $myData,
			'posts' => $posts,'subposts' => $subposts,
			'postForm' => $formPost->createview(),
			'subpostForm' => $formSubposts
			
		));
		
	}
	
	/**
	 * @Route("/omnie",name="path_about")
	 */
	 
	public function aboutAction(Request $request,DBService $db_service,CookieService $cookie_service){
	
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $userData = $db_service->getUserData($user,0);
	
		return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData));
	}
	
	/**
	 * @Route("/zdjecia",name="path_photo")
	 */
	 
	public function photoAction(DBService $db_service,CookieService $cookie_service){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $userData = $db_service->getUserData($user,0);
	
	
			return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData));
	}
	
	
	/**
	 * @Route("/znajomi",name="path_friends")
	 */
	 
	public function friendsAction(DBService $db_service,CookieService $cookie_service){
	
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $userData = $db_service->getUserData($user,0);
	
	
			return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData));
	}
	
	
	
}
	
	
	
	
	
	
	
	
	
	
?>