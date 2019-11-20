<?php

namespace AppBundle\Entity\Photos;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="albums")
 *
 */
 
class Albums{
	
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
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $title;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="string",length=1)
	 */
	 
	private $access;
	
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getuserId(){
		
		return $this->userId;
	}
	
	public function getTitle(){
		
		return $this->title;
	}
	
	public function getAccess(){
		
		return $this->access;
	}
	
	public function setUserId($userId){
		
		return $this->userId = $userId;
	}
	
	public function setTitle($title){
		
		return $this->title = $title;
	}
	
	public function setAccess($access){
		
		return $this->access = $access;
	}
}
?>