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
	
	
	
	public function homeAction(Request $request,DBService $db_service,CookieService $cookie_service,$id,$page){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		$myData = $db_service->getUserData($user,0);
		$userData = $db_service->getUserData($id,1);
		
		
		$posts = $db_service->getposts($id);
		
		$postForm = new Posts();
		$formPost = $this->createForm(PostsForm::class,$postForm);
		
		$formPost->handleRequest($request);
		
		if($formPost->isValid()){
			
			$db_service->addPost($userData->getId(),$postForm);
			
			$prefix = ceil((count($posts) + 1 ) / 5);
			
			return $this->redirect($this->generateUrl('path_home',array('page' => $prefix)));
		}
			
		$subposts = $db_service->getSubposts($posts);	
		
		$formSubposts = array();
		$subpostForm = array();
		
		foreach($posts as $post){
			$postId = $post -> getId();
			$subpostForm[$postId] = new Subposts();
			
			$formSubposts[$postId] = $this->createForm(SubpostsForm::class,
				$subpostForm[$postId],array('attr' => array('id' => $postId))
			);
			$formSubposts[$postId]->handleRequest($request);
			if($formSubposts[$postId]->isValid()){
				
				$db_service->addSubpost($myData->getId(),$postId,$subpostForm[$postId]);
			
				return $this->redirect($this->generateUrl('path_user_home',array('page' => $page,'id' => $id)));	
			}
			
			$formSubposts[$postId] = $formSubposts[$postId]->createview();
		}
		
		$comments = $db_service->getComments($id);
		
		
		return $this->render('Profile/home.html.twig',array(
			'userData' => $userData,
			'myData' => $myData,
			'posts' => $posts,'subposts' => $subposts,
			'postForm' => $formPost->createview(),
			'subpostForm' => $formSubposts,
			'comments' => $comments,
			'page' => $page
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