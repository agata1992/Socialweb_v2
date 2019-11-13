<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Login;
use AppBundle\Entity\ActivateLink;
use AppBundle\Entity\ChangePasswordLink;

use AppBundle\Entity\Posts\Posts;
use AppBundle\Entity\Posts\Subposts;

use AppBundle\Entity\Comments\Comments;
use AppBundle\Entity\Comments\Subcomments;

use AppBundle\Entity\Groups\Groups;
use AppBundle\Entity\Groups\GroupsMember;

use AppBundle\Service\AdditionalService;

class DBService{
	
	protected $requestStack;
	protected $entityManager;
	
	public function __construct(RequestStack $requestStack,EntityManager $entityManager)
	{
		$this->requestStack = $requestStack;
		$this->entityManager = $entityManager;
	}	
	
	public function checkLoginCorrect($email,$password){
		
		$row = $this->entityManager->getRepository(Login::class)->findOneBy([
			'email' => $email]);
			
		if($row == NULL)
			return 0;
		else{
		
			$salt = $row->getSalt();
			$passwordInDb = $row->getPassword();
			
			if($passwordInDb == md5($password.$salt)){
				
				if($row->getActivate() == 1)
					return 1;
				else 
					return 2;
			}
			else
				return 0;
		}	
	}
	
	public function getUserData($param,$type){
	
		$additionalService = new AdditionalService();
		
		if($type == 0)
			$row = $this->entityManager->getRepository(User::class)->findOneBy([
				'email' => $param]);
		else
			$row = $this->entityManager->getRepository(User::class)->findOneBy([
				'id' => $param]);
		
		if($row != NULL){
		
			$row->age = date_diff(new \DateTime(),$row->getBirthdate())->y;
			$ageName = $additionalService->getAgeName($row->age);
			$row->age = $row->age.' '.$ageName;
		}
		
		return $row;
	}
	
	public function setNoActivateAccount($register){
		
		$salt = uniqid();
		$register-> setSalt($salt);
		
		$newPassword = $register->getPassword().$salt;
		
		$hashedPassword = md5($newPassword);
		$register->setPassword($hashedPassword);
		$register->setActivate(0);
		
		$this->entityManager->persist($register);
		$this->entityManager->flush();
	
		$lastId = $register->getId();
		
		$row = 1;
			
		do{
			$link = uniqid();
			$row = $this->entityManager->getRepository(ActivateLink::class)->findOneBy([
				'link' => $link]);
		}
		while($row != NULL);
			
		$activateLink = new ActivateLink();
		
		$activateLink->setUserId($lastId);
		$activateLink->setLink($link);
		
		$this->entityManager->persist($activateLink);
		$this->entityManager->flush();
		
		$link = $lastId.'/'.$link;
		
		return $link;
	}
	
	public function activateAccount($userId,$link){
		
		$row = $this->entityManager->getRepository(ActivateLink::class)->findOneBy([
				'link' => $link,'userId' => $userId]);
				
		if($row == NULL)
			return 0;
		else{
		
			$userRow = $this->entityManager->getRepository(User::class)->findOneBy([
				'id' => $userId]);
				
			$userRow->setActivate(1);
			$this->entityManager->persist($userRow);
			$this->entityManager->flush();
				
			$this->entityManager->remove($row);
			$this->entityManager->flush();
				
			return 1;
		}
	}

	public function setChangePasswordLink($userId){
	
		$row = 1;
			
		do{
			$link = uniqid();
			$row = $this->entityManager->getRepository(ChangePasswordLink::class)->findOneBy([
				'link' => $link]);
				
		}
		while($row != NULL);
			
		$lastLinks = $row = $this->entityManager->getRepository(ChangePasswordLink::class)->findBy([
				'userId' => $userId]);
				
		foreach($lastLinks as $linkLast)
			$this->entityManager->remove($linkLast);
		
		$this->entityManager->flush();
		
		$changePasswordLink = new ChangePasswordLink();
		
		$changePasswordLink->setUserId($userId);
		$changePasswordLink->setLink($link);
		
		$this->entityManager->persist($changePasswordLink);
		$this->entityManager->flush();
		
		$link = $userId.'/'.$link;
		
		return $link;
	}
	
	public function checkActivateLinkExists($userId,$link){
		
		$row = $this->entityManager->getRepository(ChangePasswordLink::class)->findOneBy([
			'userId' => $userId]);
			
		if($row == NULL)
			return 0;
		else{
			
			$userRow = $this->entityManager->getRepository(User::class)->findOneBy([
				'id' => $userId,'activate' => 1]);
		
			if($row == NULL)
				return 0;
			else
				return 1;
		}
	}
	
	public function changePassword($userId,$password){
		
		$userRow = $this->entityManager->getRepository(User::class)->findOneBy([
				'id' => $userId]);
		
		$salt = uniqid();
		
		$userRow->setSalt($salt);
		
		$password = md5($password.$salt);
		$userRow->setPassword($password);
		
		$this->entityManager->persist($userRow);
		$this->entityManager->flush();
		
		$row = $this->entityManager->getRepository(ChangePasswordLink::class)->findOneBy([
				'userId' => $userId]);
				
		$this->entityManager->remove($row);
		$this->entityManager->flush();
	}

	public function getPosts($id){
		
		$row = $this->entityManager->getRepository(Posts::class)->findBy([
			'userId' => $id],['date' => 'DESC']);
			
		return $row;
		
	}
	
	public function addPost($id,$postForm){
		
		$postForm->setUserId($id);
		$postForm->setDate(new \DateTime(date("Y-m-d H:i")));
		
		$this->entityManager->persist($postForm);
		$this->entityManager->flush();
	}
	
	public function getSubposts($posts){
	
		$subposts = array();
		foreach($posts as $post){
			
			$row = $this->entityManager->getRepository(Subposts::class)->findBy([
			'postId' => $post->getId()]);
			
			$row = $this->entityManager->createQueryBuilder()
			->select('s.id,s.userId,s.date,s.text,u.name,u.surname,u.profileImg')
			->from(Subposts::class,'s')
			->leftjoin(User::class,'u','WITH','u.id = s.userId')
			->orderBy('s.date', 'DESC')
			->where('s.postId = :postId')
			->setParameter(":postId",$post->getId())
			->getQuery()->getResult();
			
			$subposts[$post->getId()] = $row;
		}
			
		return $subposts;
	}
	
	public function addSubpost($id,$postId,$subpostForm){
		
		$subpostForm->setUserId($id);
		$subpostForm->setPostId($postId);
		$subpostForm->setDate(new \DateTime(date("Y-m-d H:i")));
		
		$this->entityManager->persist($subpostForm);
		$this->entityManager->flush();
	}
	
	public function getComments($id){
		
		$row = $this->entityManager->createQueryBuilder()
			->select('c.id,c.userId,c.date,c.text,u.name,u.surname,u.profileImg')
			->from(Comments::class,'c')
			->leftjoin(User::class,'u','WITH','u.id = c.userId')
			->orderBy('c.date', 'DESC')
			->where('c.profileId = :profileId')
			->setParameter(":profileId",$id)
			->getQuery()->getResult();
			
		return $row;
	}
	
	public function addComment($profileId,$userId,$commentForm){
		
		$commentForm->setprofileId($profileId);
		$commentForm->setUserId($userId);
		$commentForm->setDate(new \DateTime(date("Y-m-d H:i")));
		
		$this->entityManager->persist($commentForm);
		$this->entityManager->flush();
	}
	
	public function getSubcomments($comments){
	
		$subcomments = array();
		foreach($comments as $comment){
			
			$row = $this->entityManager->getRepository(Subcomments::class)->findBy([
			'commentId' => $comment['id']]);
			
			$row = $this->entityManager->createQueryBuilder()
			->select('c.id,c.userId,c.date,c.text,u.name,u.surname,u.profileImg')
			->from(Subcomments::class,'c')
			->leftjoin(User::class,'u','WITH','u.id = c.userId')
			->orderBy('c.date', 'DESC')
			->where('c.commentId = :commentId')
			->setParameter(":commentId",$comment['id'])
			->getQuery()->getResult();
			
			$subcomments[$comment['id']] = $row;
		}
			
		return $subcomments;
	}
	
	public function addSubcomment($id,$commentId,$subcommentForm){
		
		$subcommentForm->setUserId($id);
		$subcommentForm->setCommentId($commentId);
		$subcommentForm->setDate(new \DateTime(date("Y-m-d H:i")));
		
		$this->entityManager->persist($subcommentForm);
		$this->entityManager->flush();
	}
	
	public function getUserGroup($id){
		
		$row = $this->entityManager->createQueryBuilder()
			->select('g.id,g.title,g.image')
			->from(Groups::class,'g')
			->leftjoin(GroupsMember::class,'m','WITH','g.id = m.groupId')
			->orderBy('g.title', 'ASC')
			->where('m.userId = :userId')
			->setParameter(":userId",$id)
			->getQuery()->getResult();
			
		return $row;
	}
}
?>