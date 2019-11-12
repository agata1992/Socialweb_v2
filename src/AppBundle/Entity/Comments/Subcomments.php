<?php

namespace AppBundle\Entity\Comments;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subcomments")
 *
 */
 
class Subcomments{
	
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
	 
	private $commentId;
	
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
	
	public function getCommentId(){
		
		return $this->commentId;
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
	
	public function setCommentId($commentId){
		
		return $this->commentId = $commentId;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
	
	public function setText($text){
		
		return $this->text = $text;
	}
}
?>