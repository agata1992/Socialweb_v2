<?php

namespace AppBundle\Entity\Posts;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 *
 */
 
class Posts{
	
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
	 * @ORM\Column(type="datetime")
	 */
	
	private $date;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 *
	 * @ORM\Column(type="text")
	 */
	
	private $text;
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getUserId(){
		
		return $this->userId;
	}
	public function getDate(){
		
		return $this->date;
	}
	
	public function getText(){
		
		return $this->text;
	}
	
	public function setUserId($userId){
		
		return $this->userId = $userId;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
	
	public function setText($text){
		
		return $this->text = $text;
	}
}
?>