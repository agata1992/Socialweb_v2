<?php

namespace AppBundle\Entity\Photos;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="photos")
 *
 */
 
class Photos{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	 
	private $albumId;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $name;
	
	/**
	 * @ORM\Column(type="text")
	 */
	 
	private $description;
	
	/**
	 * @ORM\Column(type="date")
	 */
	 
	private $date;
	
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getalbumId(){
		
		return $this->albumId;
	}
	
	public function getName(){
		
		return $this->name;
	}
	
	public function getDescription(){
		
		return $this->description;
	}
	
	public function getDate(){
		
		return $this->date;
	}
	
	public function setAlbumId($albumId){
		
		return $this->albumId = $albumId;
	}
	
	public function setName($name){
		
		return $this->name = $name;
	}
	
	public function setDescription($description){
		
		return $this->description = $description;
	}
	
	public function setDate($date){
		
		return $this->date = $date;
	}
}
?>