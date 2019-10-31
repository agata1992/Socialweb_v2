<?php
	
namespace AppBundle\Entity;
	
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="changepasswordlink")
 *
 */

class ChangePasswordLink{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
     * @ORM\Column(type="integer")
     * 
     */
	
	private $userId;
	
	/**
     * @ORM\Column(type="string")
     * 
     */
	
	private $link;
	
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getUserId(){
		
		return $this->userId;
	}
	
	public function getLink(){
		
		return $this->link;
	}
	
	public function setUserId($userId){
		
		return $this->userId = $userId;
	}
	
	public function setLink($link){
		
		return $this->link = $link;
	}
}
?>