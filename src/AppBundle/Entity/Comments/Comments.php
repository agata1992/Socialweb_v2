<?php

namespace AppBundle\Entity\Comments;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 *
 */
 
class Comments{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $profileId;
	
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
	
	public function getProfileId(){
		
		return $this->profileId;
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
	
	public function setProfileId($profileId){
		
		return $this->profileId = $profileId;
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