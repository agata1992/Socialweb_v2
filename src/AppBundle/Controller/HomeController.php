<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\FormError;

use AppBundle\Service\DBService;
use AppBundle\Service\CookieService;
use AppBundle\Service\AdditionalService;

use AppBundle\Entity\Posts\Posts;
use AppBundle\Entity\Posts\Subposts;
use AppBundle\Entity\Comments\Comments;
use AppBundle\Entity\Comments\Subcomments;
use AppBundle\Entity\Photos\Albums;
use AppBundle\Entity\Photos\PhotoComments;

use AppBundle\Form\Posts\PostsForm;
use AppBundle\Form\Posts\SubpostsForm;
use AppBundle\Form\Comments\CommentsForm;
use AppBundle\Form\Comments\SubcommentsForm;
use AppBundle\Form\Photos\AlbumForm;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\File;

use AppBundle\Entity\Friends\Friends;

class HomeController extends Controller{
	
	/**
	 * @Route("/page/{page}",name="path_home",defaults={"page":1})
	 * @Route("/user/{id}/page/{page}",name="path_user_home",defaults={"page":1})
	 */
	
	public function homeAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id = null){
		
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this -> redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
		
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		if($id == null)
			$posts = $db_service -> getPosts($myData->getId());
		else
			$posts = $db_service -> getposts($id);
		
		if($id == null){
		
			$postForm = new Posts();
			$formPost = $this -> createForm(PostsForm::class,$postForm);
		
			$formPost -> handleRequest($request);
		
			if($formPost -> isValid()){
			
				if($id == null)
					$db_service -> addPost($myData -> getId(),$postForm);
				else
					$db_service -> addPost($userData -> getId(),$postForm);
			
				$prefix = ceil((count($posts) + 1 ) / 5);
			
				if($id == null)
					return $this -> redirect($this -> generateUrl('path_home',array('page' => $prefix)));
			}
		}
			
		$subposts = $db_service -> getSubposts($posts);	
		
		$formSubposts = array();
		$subpostForm = array();
		
		foreach($posts as $post){
			$postId = $post -> getId();
			$subpostForm[$postId] = new Subposts();
			
			$formSubposts[$postId] = $this -> createForm(SubpostsForm::class,
				$subpostForm[$postId],array('attr' => array('id' => $postId))
			);
			
			$formSubposts[$postId] -> handleRequest($request);
			
			if($formSubposts[$postId] -> isValid()){
				
				$db_service->addSubpost($myData -> getId(),$postId,$subpostForm[$postId]);
				
				if($id == null)
					return $this->redirect($this -> generateUrl('path_home',array('page' => $page)));	
				else
					return $this->redirect($this -> generateUrl('path_user_home',array('page' => $page,'id' => $id)));
			}
			
			$formSubposts[$postId] = $formSubposts[$postId] -> createview();
		}
		
		if($id == null)
			$comments = $db_service -> getComments($myData -> getId());
		else
			$comments = $db_service -> getComments($id);
		
		
		$commentForm = new Comments();
		$formComment = $this->createForm(CommentsForm::class,$commentForm);
		
		$formComment -> handleRequest($request);
		
		if($formComment -> isValid()){
			
			if($id == null)
				$db_service -> addComment($myData -> getId(),$myData -> getId(),$commentForm);
			else
				$db_service->addComment($id,$myData -> getId(),$commentForm);
			
			if($id == null)
				return $this->redirect($this -> generateUrl('path_home',array('page' => $page)));
			else
				return $this->redirect($this -> generateUrl('path_user_home',array('page' => $page,'id' => $id)));
		}
		
		$subcomments = $db_service -> getSubcomments($comments);
		
		$formSubcomments = array();
		$subcommentsForm = array();
		
		foreach($comments as $comment){
			$commentId = $comment['id'];

			$subcommentsForm[$commentId] = new Subcomments();
			
			
			$formSubcomments[$commentId] = $this -> createForm(SubcommentsForm::class,
				$subcommentsForm[$commentId],array('attr' => array('id' => $commentId))
			);
			
			$formSubcomments[$commentId] -> handleRequest($request);
			
			if($formSubcomments[$commentId] -> isValid()){
			
				$db_service->addSubcomment($myData -> getId(),$commentId,$subcommentsForm[$commentId]);
				
				if($id == null)
					return $this->redirect($this -> generateUrl('path_home',array('page' => $page)));	
				else
					return $this->redirect($this -> generateUrl('path_user_home',array('page' => $page,'id' => $id)));
			}
			
			$formSubcomments[$commentId] = $formSubcomments[$commentId] -> createview();
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
				'page' => $page,
				'photoCount' => $photoCount
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
				'page' => $page,
				'photoCount' => $photoCount
			);
		}
		
		return $this->render('Profile/home.html.twig',$returnArray);
	}
	
	/**
	 * @Route("/omnie/page/{page}",name="path_about",defaults={"page":1})
	 * @Route("/user/{id}/omnie/page/{page}",name="path_user_about",defaults={"page":1})
	 */
	 
	public function aboutAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id = null){
	
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
		
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		if($id == null)
			$groups = $db_service -> getUserGroup($myData -> getId());
		else
			$groups = $db_service -> getUserGroup($userData -> getId());
	
		return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData,
			'groups' => $groups,'page' => $page,'photoCount' => $photoCount));
	}
	
	/**
	 * @Route("/zdjecia/page/{page}",name="path_galery",defaults={"page":1})
	 *
	 * @Route("/user/{id}/zdjecia/page/{page}",name="path_user_galery",defaults={"page":1})
	 */
	 
	public function galeryAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id = null){
		
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
	
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		$albums = $db_service -> getUserAlbums($myData -> getId(),$userData -> getId());
		
		$albumForm = new Albums();
		$formAlbum = $this->createForm(AlbumForm::class,$albumForm);
		
		$formAlbum->handleRequest($request);
		
		if($formAlbum -> isValid()){
			
			$name = $formAlbum['title'] -> getData();
			
			$row = $albums = $db_service -> checkAlbumExists($userData -> getId(),$name);
			
			if($row != NULL)
				$formAlbum->get('title') -> addError(new FormError('Nazwa już istnieje'));
			else{
			
				$access = $formAlbum['access'] -> getData();
					
				$db_service -> addAlbum($userData -> getId(),$name,$access,$albumForm);
					
				if($id == NULL)
					return $this->redirect($this -> generateUrl('path_photo'));
				else
					return $this->redirect($this -> generateUrl('path_user_photo'));
			}
		}
		
		return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData,
			'albums' => $albums,'albumForm' => $formAlbum -> createView(),'page' => $page,
			'photoCount' => $photoCount));
	}
	
	/**
	 * @Route("/album/{albumId}/page/{page}",name="path_album",defaults={"page":1})
	 *
	 * @Route("/user/{id}/album/{albumId}/page/{page}",name="path_user_album",defaults={"page":1})
	 */
	
	public function albumAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id=null,$albumId){
		
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
		
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		$album = $db_service -> getUserAlbum($myData -> getId(),$userData -> getId(),$albumId);
		
		$photos = $db_service -> getAlbumPhotos($albumId);
		
		if($album == NULL)
			throw $this->createNotFoundException('');
		
		$oldName = $album ->getTitle();
		
		$formAlbum = $this->createForm(AlbumForm::class,$album);
		
		$formAlbum -> handleRequest($request);
		
		if($formAlbum -> isValid()){
			
			$name = $formAlbum['title'] -> getData();
			
			$row = $albums = $db_service -> checkAlbumExists($userData -> getId(),$name);
			
			if($row != NULL && $row[0] -> getId() != $albumId){
				
				$album -> setTitle($oldName);
				$formAlbum->get('title') -> addError(new FormError('Nazwa już istnieje'));
			}
			else{
				
				$name = $formAlbum['title'] -> getData();
				$access = $formAlbum['access'] -> getData();
				
				$db_service -> addAlbum($userData -> getId(),$name,$access,$album);
			}
		}
		else
			$album -> setTitle($oldName);
		
		
		$formDeleteAlbum = $this->createFormBuilder()
			->add('submit',SubmitType::class,array('label' => 'Potwierdź'))	
			->getForm();
			
		$formDeleteAlbum -> handleRequest($request);
		
		if($formDeleteAlbum -> isValid()){
					
			foreach($photos as $photo){
			
				$name = $photo->getName();
				unlink("image/".$name);
			}
			
			$db_service -> removeAlbum($albumId);
			
			if($id == NULL)
				return $this->redirect($this -> generateUrl('path_photo'));
			else
				return $this->redirect($this -> generateUrl('path_user_photo'));
		}
		
		$formAddPhoto = $this->createFormBuilder()
			->add('photo',FileType::class,array(
				'label' => 'Dodaj zdjęcie',
				
				'attr'=>array('class'=>'btn-primary text-center'),
				'constraints' =>new File(array(
					'maxSize' => '500k',
					'mimeTypes' => array(
						'image/jpeg',
						'image/gif',
						'image/png'
					),
					'mimeTypesMessage' => 'Please upload a valid PDF document'
				))
			))
			->add('submit1',SubmitType::class)
			->getForm();
			
		$formAddPhoto ->handleRequest($request);
			
		if($formAddPhoto -> isValid()){
				
			$photo = $formAddPhoto['photo'] -> getData();
				
			$extension = $photo->guessExtension();
			
			$name = $myData -> getId().'u'.uniqid().time().'.'.$extension;
				
			$photo -> move('image/',$name);
			$db_service -> addUserPhoto($albumId,$name);
			
			
			return $this->redirect($this -> generateUrl('path_album',array('albumId' => $albumId)));
			
		}
		
		return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData,
			'album' => $album,'albumForm' => $formAlbum -> createView(),'deleteAlbumForm' => $formDeleteAlbum -> createView(),
			'addPhotoForm' => $formAddPhoto -> createView(),'photos' => $photos,'page' => $page,
			'photoCount' => $photoCount));
		
		
	}
	
	
	/**
	 * @Route("/album/{albumId}/photo/{photoId}/page/{page}",name="path_photo",defaults={"page":1})
	 *
	 * @Route("/user/{id}/album/{albumId}/photo/{photoId}/page/{page}",name="path_user_photo",defaults={"page":1})
	 */
	
	public function photoAction(Request $request,DBService $db_service,CookieService $cookie_service,$page,$id=null,$albumId,$photoId){
	
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
	
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		$album = $db_service -> getUserAlbum($myData -> getId(),$userData -> getId(),$albumId);
		
		if($album == NULL)
			throw $this->createNotFoundException('');
		
		$photo = $db_service -> getUserPhoto($photoId);
		
		if($id == null){
		
			$formChangeDescription = $this->createFormBuilder($photo)
				->add('description',TextareaType::class,array('label' => 'Opis','attr' => array('rows' => 5)))
				->add('submit',SubmitType::class,array('label' => 'Potwierdź'))	
				->getForm();
			
			$formChangeDescription -> handleRequest($request);
		
			if($formChangeDescription -> isSubmitted()){
			
				$description = $formChangeDescription['description'] -> getData();
				$db_service -> changePhotoDescription($photoId,$description);
			}
		
			$formProfileImg = $this->createFormBuilder()
				->add('submit',SubmitType::class,array('label' => 'Potwierdź'))	
				->getForm();
			
			$formProfileImg -> handleRequest($request);
		
			if($formProfileImg -> isValid()){
				
				$db_service -> setProfileImage($userData -> getId(),$photo ->getName());
			}
			
			$formDeletePhoto = $this->createFormBuilder()
				->add('submit2',SubmitType::class,array('label' => 'Potwierdź'))	
				->getForm();
			
			$formDeletePhoto -> handleRequest($request);
		
			if($formDeletePhoto -> isValid()){
		
				$db_service -> deletePhoto($userData -> getId(),$photo);
				
				return $this->redirect($this -> generateUrl('path_album',array('albumId' => $albumId)));
			}
		}
		
		$comments = $db_service -> getPhotoComments($photoId);
		
		$commentForm = new PhotoComments();
		$formComment = $this->createForm(CommentsForm::class,$commentForm);
		
		$formComment -> handleRequest($request);
		
		if($formComment ->isValid()){
			
			$db_service -> addPhotoComments($myData -> getId(),$photoId,$commentForm);
			
			if($id == NULL)
				return $this->redirect($this -> generateUrl('path_photo',array('albumId' => $albumId,'photoId' => $photoId)));
			else
				return $this->redirect($this -> generateUrl('path_user_photo',array('id' => $id,'albumId' => $albumId,'photoId' => $photoId)));	
		}
		
		
		if($id == null){
			
			$returnArray = array('userData' => $userData,'myData' => $myData,'photoCount' => $photoCount,
				'photo' => $photo,'changeDescriptionForm' => $formChangeDescription -> createView(),
				'profileImgForm' => $formProfileImg -> createView(),'deletePhotoForm' => $formDeletePhoto -> createView(),
				'commentForm' => $formComment -> createView(),'comments' => $comments,'page' => $page);
		}
		else{
			
			$returnArray = array('userData' => $userData,'myData' => $myData,'photoCount' => $photoCount,
				'photo' => $photo,'commentForm' => $formComment -> createView(),'comments' => $comments,'page' => $page);
		}
		
		
		
	
		return $this->render('Profile/home.html.twig',$returnArray);
	
	}
	
	
	/**
	 * @Route("/znajomi/page/{page}",name="path_friends",defaults={"page":1})
	 *
	 * @Route("/user/{id}/znajomi/page/{page}",name="path_user_friends",defaults={"page":1})
	 */
	 
	public function friendsAction(DBService $db_service,CookieService $cookie_service,$id=null,$page){
	
		$user = $cookie_service -> check_exist_user_cookie();
		
		if($user == '')
			return $this->redirect($this -> generateUrl('path_login'));
		
		if($id == null)
			$myData = $userData = $db_service -> getUserData($user,0);
		else{
		
			$myData = $db_service -> getUserData($user,0);
			$userData = $db_service -> getUserData($id,1);
		}
	
		$photoCount = $db_service -> getCountPhoto($userData ->getId());
		
		$friends = $db_service -> getFriends($userData -> getId());
		
		
		
		
		
	
		return $this->render('Profile/home.html.twig',array('userData' => $userData,'myData' => $myData,
		'friends' => $friends,'photoCount' => $photoCount,'page' => $page));
	}
	
	
	
}
	
	
	
	
	
	
	
	
	
	
?>