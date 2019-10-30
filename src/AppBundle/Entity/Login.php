<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 */
 
class Login{
	
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	
	private $id;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 * @Assert\Email(message = "Wpisz poprawny email")
	 *
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $email;
	
	/**
	 * @Assert\NotBlank(message = "Pole nie może być puste")
	 * 
	 * @ORM\Column(type="string",length=255)
	 */
	
	private $password;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $salt;
	
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	 
	private $activate;
	
	public function getId(){
		
		return $this->id;
	}
	
	public function getEmail(){
		
		return $this->email;
	}
	
	public function getPassword(){
		
		return $this->password;
	}
	
	public function getSalt(){
		
		return $this->salt;
	}
	
	public function getActivate(){
		
		return $this->activate;
	}
	
	public function setEmail($email){
		
		return $this->email = $email;
	}
	
	public function setPassword($password){
		
		return $this->password = $password;
	}
	
	public function setActivate(){
		
		return $this->activate = $activate;
	}
}
?>