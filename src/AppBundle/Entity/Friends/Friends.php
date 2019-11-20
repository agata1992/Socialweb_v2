<?php

namespace AppBundle\Entity\Friends;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="friends")
 *
 */
 
class Friends{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $userId;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $userId2;
	
	/**
	 * @ORM\Column(type="date")
	 */
	
	private $date;
	
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getUserId(){
		
		return $this->userId;
	}
	
	public function getUserId2(){
		
		return $this->user2Id;
	}
	
	public function getDate(){
		
		return $this->date;
	}
	
	//public function setUser1Id($user1Id){
		
	//	return $this->user1Id = $user1Id;
	//}
	
	//public function setUser2Id($user2Id){
		
	//	return $this->user2Id = $user2Id;
	//}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
}
?>