<?php

namespace AppBundle\Entity\Photos;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="photocomments")
 *
 */
 
class PhotoComments{
	
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
	 
	private $imageId;
	
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
	
	public function getuserId(){
		
		return $this->userId;
	}
	
	public function getimageId(){
		
		return $this->imageId;
	}
	public function getDate(){
		
		return $this->date;
	}
	
	public function getText(){
		
		return $this->text;
	}
	
	public function setuserId($userId){
		
		return $this->userId = $userId;
	}
	
	public function setimageId($imageId){
		
		return $this->imageId = $imageId;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
	
	public function setText($text){
		
		return $this->text = $text;
	}
}
?>