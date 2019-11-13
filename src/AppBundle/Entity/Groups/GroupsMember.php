<?php

namespace AppBundle\Entity\Groups;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groupmembers")
 *
 */
 
class GroupsMember{
	
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
	 
	private $groupId;
	
	/**
	 * @ORM\Column(type="date")
	 */
	
	private $date;
	
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getuserId(){
		
		return $this->userId;
	}
	
	public function getgroupId(){
		
		return $this->groupId;
	}
	
	public function getDate(){
		
		return $this->date;
	}
	
	public function setUserId($userId){
		
		return $this->userId = $userId;
	}
	
	public function setGroupId($groupId){
		
		return $this->groupId = $groupId;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
}
?>