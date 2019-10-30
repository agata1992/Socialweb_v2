<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Login;
use AppBundle\Entity\ActivateLink;

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
		
		return $link;
	}

}
?>