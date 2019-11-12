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
use AppBundle\Entity\Comments\Comments;
use AppBundle\Entity\Comments\Subcomments;

use AppBundle\Form\Posts\PostsForm;
use AppBundle\Form\Posts\SubpostsForm;
use AppBundle\Form\Comments\CommentsForm;
use AppBundle\Form\Comments\SubcommentsForm;

class HomeController extends Controller{
	
	/**
	 * @Route("/page/{page}",name="path_home",defaults={"page":1})
	 * @Route("/user/{id}/page/{page}",name="path_user_home",defaults={"page":1})
	 */
	
	public function homeAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id = null){
		
		$user = $cookie_service->check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this->generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service->getUserData($user,0);
		else{
		
			$myData = $db_service->getUserData($user,0);
			$userData = $db_service->getUserData($id,1);
		}
		
		if($id == null)
			$posts = $db_service->getPosts($myData->getId());
		else
			$posts = $db_service->getposts($id);
		
		if($id == null){
		
			$postForm = new Posts();
			$formPost = $this->createForm(PostsForm::class,$postForm);
		
			$formPost->handleRequest($request);
		
			if($formPost->isValid()){
			
				if($id == null)
					$db_service->addPost($myData->getId(),$postForm);
				else
					$db_service->addPost($userData->getId(),$postForm);
			
				$prefix = ceil((count($posts) + 1 ) / 5);
			
				if($id == null)
					return $this->redirect($this->generateUrl('path_home',array('page' => $prefix)));
			}
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
				
				if($id == null)
					return $this->redirect($this->generateUrl('path_home',array('page' => $page)));	
				else
					return $this->redirect($this->generateUrl('path_user_home',array('page' => $page,'id' => $id)));
			}
			
			$formSubposts[$postId] = $formSubposts[$postId]->createview();
		}
		
		if($id == null)
			$comments = $db_service->getComments($myData->getId());
		else
			$comments = $db_service->getComments($id);
		
		
		$commentForm = new Comments();
		$formComment = $this->createForm(CommentsForm::class,$commentForm);
		
		$formComment->handleRequest($request);
		
		if($formComment->isValid()){
			
			if($id == null)
				$db_service->addComment($myData->getId(),$myData->getId(),$commentForm);
			else
				$db_service->addComment($id,$myData->getId(),$commentForm);
			
			if($id == null)
				return $this->redirect($this->generateUrl('path_home',array('page' => $page)));
			else
				return $this->redirect($this->generateUrl('path_user_home',array('page' => $page,'id' => $id)));
		}
		
		$subcomments = $db_service->getSubcomments($comments);
		
		$formSubcomments = array();
		$subcommentsForm = array();
		
		foreach($comments as $comment){
			$commentId = $comment['id'];

			$subcommentsForm[$commentId] = new Subcomments();
			
			
			$formSubcomments[$commentId] = $this->createForm(SubcommentsForm::class,
				$subcommentsForm[$commentId],array('attr' => array('id' => $commentId))
			);
			
			$formSubcomments[$commentId]->handleRequest($request);
			
			if($formSubcomments[$commentId]->isValid()){
			
				$db_service->addSubcomment($myData->getId(),$commentId,$subcommentsForm[$commentId]);
				
				if($id == null)
					return $this->redirect($this->generateUrl('path_home',array('page' => $page)));	
				else
					return $this->redirect($this->generateUrl('path_user_home',array('page' => $page,'id' => $id)));
			}
			
			$formSubcomments[$commentId] = $formSubcomments[$commentId]->createview();
		}
		
		if($id == null){
		
			$returnArray = array(
				'userData' => $userData,'myData' => $myData,
				'posts' => $posts,'subposts' => $subposts,
				'postForm' => $formPost->createview(),
				'subpostForm' => $formSubposts,
				'commentForm' => $formComment->createview(),
				'comments' => $comments,
				'subcomments' => $subcomments,
				'subcommentForm' => $formSubcomments,
				'page' => $page
			);
		}
		else{
		
			$returnArray = array(
				'userData' => $userData,'myData' => $myData,
				'posts' => $posts,'subposts' => $subposts,
				'subpostForm' => $formSubposts,
				'commentForm' => $formComment->createview(),
				'comments' => $comments,
				'subcomments' => $subcomments,
				'subcommentForm' => $formSubcomments,
				'page' => $page
			);
		}
		
		return $this->render('Profile/home.html.twig',$returnArray);
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