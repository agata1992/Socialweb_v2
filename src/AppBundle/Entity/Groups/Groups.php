<?php

namespace AppBundle\Entity\Groups;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 *
 */
 
class Groups{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $ownerId;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 *
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 */
	 
	private $title;
	
	/**
	 * @ORM\Column(type="text")
	 */
	 
	private $description;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 *
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 */
	 
	private $category;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $type;
	
	/**
	 * @ORM\Column(type="date")
	 */
	
	private $date;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $image;
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getownerId(){
		
		return $this->ownerId;
	}
	
	public function getTitle(){
		
		return $this->title;
	}
	public function getDescription(){
		
		return $this->description;
	}
	
	public function getCategory(){
		
		return $this->category;
	}
	
	public function getType(){
		
		return $this->type;
	}
	
	public function getDate(){
		
		return $this->date;
	}
	
	public function getImage(){
		
		return $this->image;
	}
	
	public function setOwnerId($ownerId){
		
		return $this->ownerId = $ownerId;
	}
	
	public function setTitle($title){
		
		return $this->title = $title;
	}
	
	public function setDescription($description){
		
		return $this->description = $description;
	}
	
	public function setCategory($category){
		
		return $this->category = $category;
	}
	
	public function setType($type){
		
		return $this->type = $type;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
	
	public function setImage($image){
		
		return $this->image = $image;
	}
}
?>