<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Login;
use AppBundle\Entity\ActivateLink;
use AppBundle\Entity\ChangePasswordLink;


class DBService
{
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
	public function getUserData($email){
		$row = $this->entityManager->getRepository(User::class)->findOneBy([
			'email' => $email]);
		
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
}
?>